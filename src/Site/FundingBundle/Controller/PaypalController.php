<?php

namespace Site\FundingBundle\Controller;

use Site\FundingBundle\Entity\Funding;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PaypalController extends Controller
{
    /**
     * @Route("/paypal/IPNListener", name="_paypal_ipn")
     * @Template()
     */
    public function IPNListenerAction(Request $request)
    {
        $f = fopen("/var/www/html/simplegiving-dev/app/logs/paypal_notify.log", "a");
        fwrite($f, "\n");
        fwrite($f, date("Y-m-d H:i:s") . " PAYPAL TRANSACTION\n");
        foreach ($_POST as $key => $value) {
          fwrite($f, $key . " = " . $value . "\n");
        }
        // STEP 1: Read POST data
 
        // reading posted data from directly from $_POST causes serialization 
        // issues with array data in POST
        // reading raw POST data from input stream instead. 
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
          $keyval = explode ('=', $keyval);
          if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]); 
        }

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
          $get_magic_quotes_exists = true;
        } 
        foreach ($myPost as $key => $value) {        
          if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
            $value = urlencode(stripslashes($value)); 
          } else {
            $value = urlencode($value);
          }
          $req .= "&$key=$value";
        }
 
        // STEP 2: Post IPN data back to paypal to validate
        $ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
 
        // In wamp like environments that do not come bundled with 
        // root authority certificates,
        // please download 'cacert.pem' from 
        // "http://curl.haxx.se/docs/caextract.html" and set the directory path 
        // of the certificate as shown below.
        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        if( !($res = curl_exec($ch)) ) {
          // error_log("Got " . curl_error($ch) . " when processing IPN data");
          curl_close($ch);
          exit;
        }
        curl_close($ch);
 
 
        // STEP 3: Inspect IPN validation result and act accordingly
 
        if (strcmp ($res, "VERIFIED") == 0) {
          fwrite($f, date("Y-m-d H:i:s") . " Valid IPN data from " .
            $_SERVER['REMOTE_ADDR'] . "\n");
          $funding = new Funding;

          $donor = $this->getDonorbyEmail($_POST['payer_email']);
          if (is_object($donor)) {
            fwrite($f, date("Y-m-d H:i:s") . " Donor found: " 
              . $donor->getId() . "\n") ;
            $funding->setDonor($donor);
          } else {
            fwrite($f, date("Y-m-d H:i:s") . " Donor not found\n") ;
          }

          $matches = array();
          preg_match("/(\d+)$/", $_POST['item_name'], $matches); 
          $beneficiary = $this->getBeneficiary($matches[1]);
          if (is_object($beneficiary)) {
            fwrite($f, date("Y-m-d H:i:s") . " Beneficiary found: " 
              . $beneficiary->getId() . "\n") ;
            $funding->setBeneficiary($beneficiary);
          } else {
            fwrite($f, date("Y-m-d H:i:s") . " Beneficiary not found\n") ;
            return new Response('');
          }

          $status = $this->getDoctrine()
            ->getRepository('SiteFundingBundle:FundingStatus')
            ->findOneById('2');
          $funding->setStatus($status);

          $method = $this->getDoctrine()
            ->getRepository('SiteFundingBundle:FundingMethod')
            ->findOneById('1');

          $funding->setMethod($method);


          $funding->setAmount($_POST['mc_gross']);

          $need = $beneficiary->getNeed();
          $need -= $_POST['mc_gross'];
          $beneficiary->setNeed($need);
          fwrite($f, date("Y-m-d H:i:s") . " Amount deducted from need\n") ;

          $em = $this->getDoctrine()->getEntityManager();
          if ($need <= 0) {

              fwrite($f, date("Y-m-d H:i:s") . " Need is $0 or less\n") ;
              $searchResults = $this->getDoctrine()
                ->getRepository('SiteSearchBundle:SearchResult')
                ->findBy(array('beneficiary' => $beneficiary));
              fwrite($f, date("Y-m-d H:i:s") . " " . count($searchResults) .
                " search results found.\n");

              foreach ($searchResults as $searchResult) {
                  $em->remove($searchResult);
                  fwrite($f, date("Y-m-d H:i:s") . 
                    " Beneficiary removed from search results\n") ;
              }

              $beneficiary->setSearchable(0);
              fwrite($f, date("Y-m-d H:i:s") . 
                " Beneficiary set to not searchable\n");

              if (is_object($donor)) {
                $searchCap = $donor->getSearchCap();
                $donor->setSearchCap($searchCap + 1);
                fwrite($f, date("Y-m-d H:i:s") . 
                    " Donors search cap increased\n") ;
              }

          }

          if ($need > 0) {
              $beneficiary->setSearchable(1);
              fwrite($f, date("Y-m-d H:i:s") . 
                " Beneficiary set to searchable\n");

          }
 
          $funding->setCurrency($_POST['mc_currency']);
          $funding->setInitiated(new \DateTime(date("Y-m-d H:i:s")));
          $funding->setMethod($this->getDoctrine()
            ->getRepository('SiteFundingBundle:FundingMethod')
            ->findOneById('1'));

          $notes = '';
          foreach ($_POST as $key => $value) {
              $notes .= $key . " = " . $value . "\n";
          }
          $funding->setNotes($notes);

          $em->persist($funding);
          $em->flush();

          fclose($f);
          return new Response('');

        } else {
          fwrite($f, date("Y-m-d H:i:s") . " Invalid IPN data from " .
            $_SERVER['REMOTE_ADDR'] . "\n");
          fclose($f);
          return new Response('INVALID');
        }
    }

    private function getDonorbyEmail($email) {
      $user = $this->getDoctrine()
        ->getRepository('SiteUserBundle:User')
        ->findOneByEmail($email);
      if (is_object($user)) {
        return $this->getDoctrine()
          ->getRepository('SiteDonorBundle:Donor')
          ->findOneBy(array('user' => $user->getId()));
      } else {
        return 0;
      }
    }

    private function getBeneficiary($id) {
      return $this->getDoctrine()
        ->getRepository('SiteBeneficiaryBundle:Beneficiary')
        ->findOneById($id);
    }
}

        
