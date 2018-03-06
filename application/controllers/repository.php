<?php
class repository extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        session_start();

    }

    private $limit = 10;

    public function index()
    {
        $date = date_default_timezone_set('US/Eastern');
        $this->load->model('repository_model');
        $data['keywords'] = $this->repository_model->getKeywords();
        $data["searchString"] = "";
        $this->load->view('search', $data);

    }

    /*
    * Function to uploaded_papers thesis documents.
    *
    */

	 public function ack(){
	 	$this->load->view('ack');
	 }

   public function insertDetails()
    {
        date_default_timezone_set('US/Eastern');
        $date = date("m/d/Y");
        $this->load->model('repository_model');
        $tags = json_decode($_POST['tags']);
        //   $is_tagsMapped = false;
        $tagstoMap = array();
        if (sizeof($tags) > 0) {
            foreach ($tags as $tag) {

                array_push($tagstoMap, $tag);

 /*               $tagid = $this->repository_model->verifyTag($tag);
                if ($tagid == 0) {
                    $new_tagId = $this->repository_model->addATag($tag);

                    if ($new_tagId > 0) {
                        array_push($tagstoMap, $new_tagId);
                        //  $tagstoMap.add($new_tagId);
                    }
                } else {
                    // $tagstoMap.add($tagid);
                }*/
            }
        }
        $status = 1; // submitted by author
        $paperId = mt_rand(100000, 999999);

        // $this->generatePaperId();
         if($_POST['link']){

             $paperId = $this->repository_model->insertDetailsWithLink($paperId, $_POST['name'], $_POST['cwid'], $_POST['title'], $_POST['email'], $date, $status, $_POST['abstract'], $_POST['licenseId'],$_POST['deptId'], $_POST['collectionId'],$_POST['year'],$_POST['link'] );

         }else {
             $paperId = $this->repository_model->insertDetails($paperId, $_POST['name'], $_POST['cwid'], $_POST['title'], $_POST['email'], $date, $status, $_POST['abstract'], $_POST['licenseId'], $_POST['deptId'], $_POST['collectionId'], $_POST['year']);
         }
        if ($paperId > 0) {
            if (sizeof($tagstoMap) > 0) {
                foreach ($tagstoMap as $tagtoMap) {
                    $result = $this->repository_model->mapTag($paperId, $tagtoMap);
                    if ($result != 1) {

                        echo 0;
                    }
                }
            }
            if ($result == 1) {


                    echo $paperId;

                } else {

                    echo 0;
                }

            }
            else {

                echo $paperId;

            }

    }
    public function generatePaperId()
    {

        $this->load->model('repository_model');
        $id = mt_rand(10000, 99999);
        $paperId = $this->repository_model->checkPaperId($id);
        if ($paperId) {
            $id = mt_rand(10000, 99999);
            echo $id;
        } else {

            $id = mt_rand(10000, 99999);
            $paperId = $this->repository_model->checkPaperId($id);
            if ($paperId) {

                $id = mt_rand(10000, 99999);
                echo $id;

            }

        }
    }

    /* public function loadTags()
    {
        $this->load->model('repository_model');
        $data['tags'] = $this->repository_model->getTags();
        $this->load->view('tag', $data);
    }

    /*public function loadKeywords()
    {
        $this->load->model('repository_model');
        $data['keywords'] = $this->repository_model->getKeywords();
        $this->load->view('keywords', $data);
    }*/


    public function addATag()
    {
        /*$tagname = $_POST['tagname'];
        $this->load->model('repository_model');
        $result = $this->repository_model->addATag($tagname);
        echo $result;*/
        $tagname = $_POST['tagname'];
        $this->load->model('repository_model');
        $tagid = $this->repository_model->verifyTag($tagname);
        //echo $tagid;
        if ($tagid == 0) {
            $result = $this->repository_model->addATag($tagname);
            if($result == 1){
                $tagid = $this->repository_model->verifyTag($tagname);

                echo $tagid;
            }
        } else {
            echo $tagid;
        }
    }

    public function upload()
    {
        $this->load->model('repository_model');
        //$data['keywords'] = $this->repository_model->getKeywords();
        $data['departments'] = $this->repository_model->getDepartments();
        $data['collections'] = $this->repository_model->getCollections();

        $this->load->view('repository',$data);
    }


    public function searchByTag()
    {
        $this->load->model('repository_model');
        $q = $this->input->get('q');
        $data['result'] = $this->repository_model->searchByTag($q);
        //  $data['result1'] = $this ->repository_model -> searchByName($q);
        $this->load->view('results', $data);
    }

    public function searchResultsByTag()
    {
        $this->load->model('repository_model');
        $data['searchString'] = "";
        $data['keywords'] = $this->repository_model->getKeywords();
        $data['searchString'] = $this->input->get('q');
        $this->load->view('search', $data);
    }

    public function searchResultsByKeyWord()
    {
        $this->load->model('repository_model');
        $data['searchString'] = "";
        $data['keywords'] = $this->repository_model->getKeywords();
        $data['searchString'] = $this->input->get('q');
        $this->load->view('search', $data);
    }

    public function testquery()
    {

        $this->load->model('repository_model');
        $q = $this->input->get('q');
        $keywordType = "";
        $keywordResult = $this->repository_model->searchKeywords($q);
        if ($keywordResult) {
            $keywordResult = json_decode(json_encode($keywordResult), true);
            foreach ($keywordResult as $keyword) {

                $keywordType = $keyword['type'];
            }
            echo $keywordType;

        }

    }


/*    public function searchKeyWords()
    {
        $this->load->model('repository_model');
        $q = $this->input->get('q');
        $keywordType = "";
        $keywordResult = $this->repository_model->searchKeywords($q);
        if ($keywordResult) {
            $keywordResult = json_decode(json_encode($keywordResult), true);
            foreach ($keywordResult as $keyword) {

                $keywordType = $keyword['type'];
            }
        }
        if ($keywordType == "t") {
            $data['result'] = $this->repository_model->searchByTag($q);
            $this->load->view('results', $data);
        } else if ($keywordType == "ti") {
            $data['result'] = $this->repository_model->searchByPaper($q);
            $this->load->view('results', $data);
        } else if ($keywordType == "n") {

            $data['result'] = $this->repository_model->searchByName($q);
            $this->load->view('results', $data);
        } else {
            $data['result'] = $this->repository_model->searchByPaper($q);
            $this->load->view('results', $data);
        }
        //if($keywordResult[0]== 't') {

        //}
    }*/

    /*
   * Email user with $link
   * required $user_name,$email_id,$link
   *
  */
 public function updatePaperStatus()
 {
     $this->load->model('repository_model');
     $status = 3;
     $paperId = $this -> input -> get('paperid');
     $res = $this->repository_model->updatePaperStatus($paperId,$status);
      echo $res;
 }
  public function searchKeyWords()
 {
    $key = $this -> input -> get('key');
    // $key = trim($key);
	$key = str_replace(" ","%20", $key);
	$key = str_replace("fq","&fq", $key);
    $resultsLink = "http://35.162.165.138:8983/solr/repo/select?facet.field=Collection&facet.field=Year&facet.field=Department&facet=true&indent=on&q=".$key."&wt=json&rows=1000";
    // echo $resultsLink;
    $json = file_get_contents($resultsLink);
    $data['results'] = json_decode($json);
	//$data['searchTerm'] = $key;
    $this->load->view('results', $data);
 }

    public function email_user()
    {

        $this->load->library('email');
        $this->load->model('repository_model');
        $config['protocol'] = "sendmail";
        $config['smtp_host'] = "tls://smtp.googlemail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "maristarchives@gmail.com";
        $config['smtp_pass'] = "redfoxesArchives";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $this->email->initialize($config);
        $name = $_POST["name"];
        $email_id = $_POST["email"];
        $paperId = $_POST['paperid'];
        $this->email->from('maristarchives@gmail.com', ' Marist Archives');
        $this->email->to($email_id);
        if(!empty($_FILES['file_attach'])) {
            $file_info = pathinfo($_FILES['file_attach']['name']);
            $url = "http://148.100.181.189:8090/repository/uploaded/" . $paperId . "." . $file_info['extension'];
        }else if(!empty($_POST["link"])){
            $url = $_POST["link"];
        }
        $res = $this->repository_model->updateLink($url, $paperId);
        if($res) {
            $link = "http://library.marist.edu/repository?c=repository&m=fileInfo&id=".$paperId;
            // $this ->email ->bcc("monish.singh1@marist.edu");
            $this->email->subject('Honors Thesis Paper');

            $emailBody = '<html><body>';

            $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';

            $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";

            $emailBody .= "<h4>Dear $name,<br/><br/> Your Paper has been uploaded successfully and is awaiting approval. You will be notified once the paper is approved. </h4></br></tr>";

            $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>*Please contact adminstrator if you find any issues.</I></td></tr>";

            //$message .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";
            $emailBody .= "</table>";

            $emailBody .= "</body></html>";

            //  $this->email->cc('dheeraj.karnati1@marist.edu');
            //  $this->email->cc('another@another-example.com');
            //$this->email->bcc('them@their-example.com');
            $this->email->message($emailBody);

            if ($this->email->send()) {

                echo $paperId;
            } else {
                return 0;
            }
        }else{

            return 0;
        }

    }


    public function email_resub($name, $link, $email_id)
    {

        $this->load->library('email');
        $config['protocol'] = "sendmail";
        $config['smtp_host'] = "tls://smtp.googlemail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "maristarchives@gmail.com";
        $config['smtp_pass'] = "redfoxesArchives";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $this->email->initialize($config);
        $this->email->from('maristarchives@gmail.com', ' Marist Archives');
        $this->email->to('maristarchives@gmail.com');
        // $this ->email ->bcc("monish.singh1@marist.edu");
        $this->email->subject('Honors Thesis Paper');

        $emailBody = '<html><body>';

        $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';

        $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";

        $emailBody .= "<br/><br/> <h4>$name has been resubmitted paper. Please click on the following link to review the paper.</h4><br/> <I>Link:</I><br/><a href='$link'> $link</a> </></br></br><h5></h5> <</tr>";

        $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I></I></td></tr>";

        //$message .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";
        $emailBody .= "</table>";

        $emailBody .= "</body></html>";

        //  $this->email->cc('dheeraj.karnati1@marist.edu');
        //  $this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');
        $this->email->message($emailBody);

        if ($this->email->send()) {

            return 1;
        } else {
            return 0;
        }

    }

    public function fileInfo()
    {

        $this->load->model('repository_model');
        $id = $this->input->get('id');
        $paperinfo = $this->repository_model->getPaperDetails($id);
        if ($paperinfo) {
            $paperinfo_dec = json_decode(json_encode($paperinfo), true);
            foreach ($paperinfo_dec as $paper) {
                $status = $paper['status'];
                $license = $paper['id'];
                $paper['paperid'];
                $author = $paper['name'];
            }
            $associatedTags = $this->repository_model->getPaperTags($id);
            if ($status != 3 && $license !=3) {
                $data['paperinfo'] = $paperinfo_dec;
                $data['associatedTags'] = json_decode(json_encode($associatedTags), true);
                $this->load->view('file_view', $data);
            } else if($status != 3 && $license ==3){
                //$sid = SID; //Session ID #
                //if the last session was over 15 minutes ago
                if (isset($_SESSION['LAST_SESSION']) && (time() - $_SESSION['LAST_SESSION'] > 900)) {
                    $_SESSION['CAS'] = false; // set the CAS session to false
                }

                $authenticated = $_SESSION['CAS'];
                $casurl = "http%3A%2F%2Flibrary.marist.edu%2Frepository%2F%3Fc%3Drepository%26m%3DfileInfo%26id=$id";
                //  $casurl = html_entity_encode($casurl);
                //send user to CAS login if not authenticated
                if (!$authenticated) {
                    $_SESSION['LAST_SESSION'] = time(); // update last activity time stamp
                    $_SESSION['CAS'] = true;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas/?service='.$casurl.'">';
                    //header("Location: https://cas.iu.edu/cas/login?cassvc=IU&casurl=$casurl");
                    exit;
                }

                if ($authenticated) {
                    if (isset($_GET["ticket"])) {
                        //set up validation URL to ask CAS if ticket is good
                        $_url = "https://login.marist.edu/cas/validate";
                        //  $serviceurl = "http://localhost:9090/repository-2.0/?c=repository&m=cas_admin";
                        // $cassvc = 'IU'; //search kb.indiana.edu for "cas application code" to determine code to use here in place of "appCode"
                        //$ticket = $_GET["ticket"];
                        $params = "ticket=$_GET[ticket]&service=$casurl";
                        $urlNew = "$_url?$params";

                        //CAS sending response on 2 lines. First line contains "yes" or "no". If "yes", second line contains username (otherwise, it is empty).
                        $ch = curl_init();
                        $timeout = 5; // set to zero for no timeout
                        curl_setopt ($ch, CURLOPT_URL, $urlNew);
                        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                        ob_start();
                        curl_exec($ch);
                        curl_close($ch);
                        $cas_answer = ob_get_contents();
                        ob_end_clean();

                        //split CAS answer into access and user
                        list($access,$user) = preg_split("/\n/",$cas_answer,2);
                        $access = trim($access);
                        $user = trim($user);
                        //set user and session variable if CAS says YES
                        if ($access == "yes") {
                            $_SESSION['user'] = $user;

                                $data['paperinfo'] = $paperinfo_dec;
                                $data['associatedTags'] = json_decode(json_encode($associatedTags), true);
                                $this->load->view('file_view', $data);



                        }//END SESSION USER
                        else{
                            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas?service='.$casurl.'">';
                        }
                    } else  {
                        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas?service='.$casurl.'">';
                    }
                }


               // $this->load->view('resubmit', $data);
            }else if($status ==3 && $license ==3){
                if (isset($_SESSION['LAST_SESSION']) && (time() - $_SESSION['LAST_SESSION'] > 900)) {
                    $_SESSION['CAS'] = false; // set the CAS session to false
                }

                $authenticated = $_SESSION['CAS'];
                $casurl = "http%3A%2F%2Flibrary.marist.edu%2Frepository%2F%3Fc%3Drepository%26m%3DfileInfo%26id=$id";
                //  $casurl = html_entity_encode($casurl);
                //send user to CAS login if not authenticated
                if (!$authenticated) {
                    $_SESSION['LAST_SESSION'] = time(); // update last activity time stamp
                    $_SESSION['CAS'] = true;
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas/?service='.$casurl.'">';
                    //header("Location: https://cas.iu.edu/cas/login?cassvc=IU&casurl=$casurl");
                    exit;
                }

                if ($authenticated) {
                    if (isset($_GET["ticket"])) {
                        //set up validation URL to ask CAS if ticket is good
                        $_url = "https://login.marist.edu/cas/validate";
                        //  $serviceurl = "http://localhost:9090/repository-2.0/?c=repository&m=cas_admin";
                        // $cassvc = 'IU'; //search kb.indiana.edu for "cas application code" to determine code to use here in place of "appCode"
                        //$ticket = $_GET["ticket"];
                        $params = "ticket=$_GET[ticket]&service=$casurl";
                        $urlNew = "$_url?$params";

                        //CAS sending response on 2 lines. First line contains "yes" or "no". If "yes", second line contains username (otherwise, it is empty).
                        $ch = curl_init();
                        $timeout = 5; // set to zero for no timeout
                        curl_setopt ($ch, CURLOPT_URL, $urlNew);
                        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                        ob_start();
                        curl_exec($ch);
                        curl_close($ch);
                        $cas_answer = ob_get_contents();
                        ob_end_clean();

                        //split CAS answer into access and user
                        list($access,$user) = preg_split("/\n/",$cas_answer,2);
                        $access = trim($access);
                        $user = trim($user);
                        //set user and session variable if CAS says YES
                        if ($access == "yes") {
                            $_SESSION['user'] = $user;

                            $data['paperinfo'] = $paperinfo_dec;
                            $data['associatedTags'] = json_decode(json_encode($associatedTags), true);
                            $data['departments'] = $this->repository_model->getDepartments();
                            $data['collections'] = $this->repository_model->getCollections();
                            $this->load->view('resubmit', $data);

                            /*   $query = $this->repository_model->allPapers($this->limit, $dept_id);
                               $total_rows = $this->repository_model->count();
                               $this->load->helper('app');
                               $pagination_links = pagination($total_rows, $this->limit,$dept_id);
                               $this->load->view('admin_view', compact('query', 'pagination_links', 'total_rows','dept_id'));*/

                        }//END SESSION USER
                        else{
                            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas?service='.$casurl.'">';
                        }
                    } else  {
                        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas?service='.$casurl.'">';
                    }
                }


            }else{

                $data['paperinfo'] = $paperinfo_dec;
                $data['associatedTags'] = json_decode(json_encode($associatedTags), true);
                $data['departments'] = $this->repository_model->getDepartments();
                $data['collections'] = $this->repository_model->getCollections();
                $this->load->view('resubmit', $data);
            }
        } else {
            $this->load->view('pagenotfound');
        }
    }

    public function view_file()
    {
        $this->load->model('repository_model');
        $id = $this->input->get('id');
        $paperinfo = $this->repository_model->getPaperDetails($id);
        if ($paperinfo) {
            $paperinfo_dec = json_decode(json_encode($paperinfo), true);
            foreach ($paperinfo_dec as $paper) {
                $status = $paper['status'];
                $paper['paperid'];
                $author = $paper['name'];
            }
            $associatedTags = $this->repository_model->getPaperTags($id);
            $data['paperinfo'] = $paperinfo_dec;
            $data['status'] = $status;
            $data['author'] = $author;
            $data['associatedTags'] = json_decode(json_encode($associatedTags), true);
            $this->load->view('file', $data);
        } else {
            $this->load->view('pagenotfound');

        }
    }

    public function getHistory()
    {
        $this->load->model('repository_model');

        $id = $this->input->get('id');
        $history_array = $this->repository_model->getHistory($id);
        if ($history_array > 0) {

            $history = json_decode(json_encode($history_array), true);
            print_r($history);
        }

    }

    public function reviewPaper()
    {

        $this->load->model('repository_model');
        $id = $this->input->get('id');

        $paperinfo = $this->repository_model->getPaperDetails($id);
        if ($paperinfo) {
            $data['paperinfo'] = json_decode(json_encode($paperinfo), true);
            $associatedTags = $this->repository_model->getPaperTags($id);
            $history_array = $this->repository_model->getHistory($id);
            if ($history_array > 0) {

                $history = json_decode(json_encode($history_array), true);
                $data['history'] = $history;
            }
            $data['associatedTags'] = json_decode(json_encode($associatedTags), true);
            $this->load->view('reviewpaper', $data);
        } else {


            $this->load->view('pagenotfound');
        }
    }


    public function substring()
    {
        $id = $this->input->get('id');
        $paperid = substr($id, -1);
        $id = substr($id, 0, 5);

        echo $id . $paperid;

    }


    public function approvePaper()
    {

        $id = $this->input->get('id');
        $name = $_POST['name'];
        $email = $_POST['email'];
        $this->load->model('repository_model');
        $comments = $_POST['comments'];
        $status = 2;
        $result = $this->repository_model->updatePaperStatus($id, $status);
        $url = "http://library.marist.edu/repository?c=repository&m=fileInfo&id=".$id;
        if ($result == 1) {
            $this->load->library('email');
            $config['protocol'] = "sendmail";
            $config['smtp_host'] = "tls://smtp.googlemail.com";
            $config['smtp_port'] = "465";
            $config['smtp_user'] = "maristarchives@gmail.com";
            $config['smtp_pass'] = "redfoxesArchives";
            $config['charset'] = "utf-8";
            $config['mailtype'] = "html";
            $config['newline'] = "\r\n";
            $this->email->initialize($config);
            $this->email->from('maristarchives@gmail.com', ' Marist Archives');
            $this->email->to($email);
            // $this ->email ->bcc("monish.singh1@marist.edu");
            $this->email->subject('Honors Thesis Paper');
            /***************************************************************************************************/
            $emailBody = '<html><body>';
            $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';
            $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";
            $emailBody .= "<br/><br/> <h4>Dear $name,<br/><br/> Your paper has been approved successfully and available to search.</h4><br/></tr>";
            $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";
            $emailBody .=  "<tr><td colspan=2 font='colr:#3A5896;'><I>Comments:<br></I><h4>$comments</h4></td></tr>";
            $emailBody .= "</table>";
            $emailBody .= "</body></html>";
            /***************************************************************************************************/

            $this->email->message($emailBody);
            if ($this->email->send()) {

                echo 1;
            } else {

                echo 0;
            }
        } else {

            echo 0;
        }

    }

    public function returnPaper()
    {
        $id = $this->input->get('id');
        $name = $_POST['name'];
        $email = $_POST['email'];
        $comments = $_POST['comments'];
        $date = date("m/d/Y");

        $this->load->model('repository_model');
        $status = 3;
        $result = $this->repository_model->updatePaperStatus($id, $status);

        if ($result == 1) {

            $message = $this->repository_model->saveToHistory($id, $status, $comments, $date);

            if ($message == 1) {

                $this->load->library('email');
                $config['protocol'] = "sendmail";
                $config['smtp_host'] = "tls://smtp.googlemail.com";
                $config['smtp_port'] = "465";
                $config['smtp_user'] = "maristarchives@gmail.com";
                $config['smtp_pass'] = "redfoxesArchives";
                $config['charset'] = "utf-8";
                $config['mailtype'] = "html";
                $config['newline'] = "\r\n";
                $this->email->initialize($config);
                $this->email->from('maristarchives@gmail.com', ' Marist Archives');
                $this->email->to($email);
                $url = "http://library.marist.edu/repository?c=repository&m=fileinfo&id=$id";
                // $this ->email ->bcc("monish.singh1@marist.edu");
                $this->email->subject('Honors Thesis Paper');
                /***************************************************************************************************/
                $emailBody = '<html><body>';
                $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';
                $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";
                $emailBody .= "<br/><br/> <h4>Dear $name,<br/><br/> Your paper has been returned. Please resubmit the paper using below link .</h4><br/></td></tr>";
                $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";
                $emailBody .=  "<tr><td colspan=2 font='colr:#3A5896;'><I>Comments:<br></I><h4>$comments</h4></td></tr>";
                $emailBody .= "</table>";
                $emailBody .= "</body></html>";
                /***************************************************************************************************/

                $this->email->message($emailBody);
                if ($this->email->send()) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {

                echo 0;
            }
        } else {

            echo 0;
        }
    }

    public function test()
    {

        $this->load->model('repository_model');
        $query = $this->repository_model->allPapers($this->limit);
        print_r($query);

    }

/*    public function checkUser($user){

        $this->load->model('repository_model');
        $result = $this->repository_model->getAdmin($user);
        foreach ($result as $r) {
            echo $r->dept_id;
        }
    }*/
/*
    public function admin()
    {

        $this->load->model('repository_model');
    //    $query = $this->repository_model->allPapers($this->limit);
     //   $total_rows = $this->repository_model->count();
     //   $this->load->helper('app');
     //   $pagination_links = pagination($total_rows, $this->limit);
        $this->load->view('admin_view');

    }*/
     public function checkAdmin(){
         $email= $this->input->get('email');

         $this->load->model('repository_model');
         $result = $this->repository_model->getAdmin($email);
         foreach ($result as $r) {
             echo $r->dept_id;
         }
     }
    public function admin_verify()
    {

        $apasscode = $this->input->get('pass');
        $email= $this->input->get('email');
        $this->load->model('repository_model');
        $passcode = $this->repository_model->getPasscode(1);
        $result = $this->repository_model->getAdmin($email);
        if ($passcode == $apasscode) {

            if($result) {
                $authorized = 1;
            }else{
                $authorized = 0;
            }
        } else {
            $authorized = 0;
        }
        echo $authorized;
    }

    public function pages()
    {
        $this->load->model('repository_model');
        $dept_id = $this -> input ->get('dept_id');
        $query = $this->repository_model->allPapers($this->limit,$dept_id);
        $total_rows = $this->repository_model->count($dept_id);
        $this->load->helper('app');
        $pagination_links = pagination($total_rows, $this->limit, $dept_id);
        $this->load->view('page_view', compact('query', 'pagination_links', 'total_rows', 'dept_id'));
    }



    public function papers()
    {
        $status = $this->input->get('status');
        $dept_id = $this->input->get('dept_id');

        if ($status != null) {

            $url = base_url("?c=repository&m=papers&status=$status&dept_id=$dept_id");

        } else {

            $url = null;
        }
        $this->load->model('repository_model');
        $query = $this->repository_model->papersWithStatus($this->limit, $status,$dept_id);
        $total_rows = $this->repository_model->countWithStatus($status,$dept_id);
        $this->load->helper('status');
        $pagination_links = pagination($total_rows, $this->limit,$dept_id, $url);
        $this->load->view('page_view', compact('query', 'pagination_links', 'total_rows','dept_id'));

    }
 public function verifyPaperTag(){
     $this->load->model('repository_model');

     $result = $this->repository_model->getPaperTag(251, 634083);
     $tid = 0;

     if($result) {
         $result = json_decode(json_encode($result), true);
         foreach ($result as $row) {

             $tid = $row['tid'];

         }
     }
     if(!$tid){
         echo "false";
     }else{
         echo $tid;
     }
 }
 public function checkAssociatedTags(){
     $paperId = $this -> input ->get('paperid');
     $this->load->model('repository_model');
     $associatedTags = $this->repository_model->getAssociatedTags($paperId);
     $associatedTags = json_decode(json_encode($associatedTags), true);
    // print_r($associatedTags);
         foreach ($associatedTags as $tag) {
             if (in_array('Barbecuing',$tag, true)) {
                 echo 1;
             } else {
                 echo 0;
             }
         }
 }
    public function updateDetails()
    {
        date_default_timezone_set('US/Eastern');
        $date = date("m/d/Y");
        $this->load->model('repository_model');
        $tags = json_decode($_POST['tags']);
        $paperId = $_POST['paperid'];
        $associatedTags = $this->repository_model-> getPaperTags($paperId);
        $associatedTags = json_decode(json_encode($associatedTags), true);
        if(sizeof($associatedTags)> 0) {
            $result = $this->repository_model-> deletePaperTags($paperId);
        }else{
            $result = $paperId;
        }
        $tagstoMap = array();
        if ($result == $paperId){
            if (sizeof($tags) > 0) {
                foreach ($tags as $tag) {

                 //   $tagid = $this->repository_model->verifyTagId($tag);
                   /* if ($tagid == 0) {

                        $new_tagId = $this->repository_model->addATag($tag);

                        if ($new_tagId > 0) {

                            array_push($tagstoMap, $new_tagId);
                        }
                    } else if($tagid > 0){*/

                        /*   $paperTagId = 0;
                         $PaperTagresult = $this->repository_model->getPaperTag($tagid, $paperId);
                          if ($PaperTagresult) {
                             $PaperTagresult = json_decode(json_encode($PaperTagresult), true);
                             foreach ($PaperTagresult as $row) {

                                 $paperTagId = $row['tid'];
                             }
                         }*/
                       // if (!$paperTagId) {
                             if($tag>0) {
                                 array_push($tagstoMap, $tag);
                             }
                        //}

                    //}
                }
            }
    }
        $status = 1;
        if ($_POST['link']) {

            $paperId = $this->repository_model->updateDetails($paperId ,$_POST['title'], $_POST['cwid'],$_POST['name'], $_POST['email'], $date, $status, $_POST['abstract'], $_POST['licenseId'], $_POST['deptId'], $_POST['collectionId'], $_POST['year'], $_POST['link']);

        } else {
            $paperId = $this->repository_model->updateDetails($paperId, $_POST['title'], $_POST['cwid'], $_POST['name'], $_POST['email'], $date, $status, $_POST['abstract'], $_POST['licenseId'], $_POST['deptId'], $_POST['collectionId'], $_POST['year'], $_POST['link']);
        }

        //  $paperId = $this->repository_model->updateDetails($paperId, $_POST['title'], $date, $status, $_POST['abstract']);
        if ($paperId > 0) {
            if (sizeof($tagstoMap) > 0) {
                foreach ($tagstoMap as $tagtoMap) {
                    $result = $this->repository_model->mapTag($paperId, $tagtoMap);
                    if ($result != 1) {

                        echo 0;
                    }
                }
            }else{

                $result = 1;
            }
            if ($result == 1) {


                echo $paperId;

            } else {

                echo 0;
            }

        } else {

            echo $paperId;

        }
}


    public function ftpp(){
        $this->load->library('ftp');
       	$ftp_config['hostname'] = 'http://148.100.181.189';
        //  $ftp_config['port'] = 8080;
        $ftp_config['username'] = 'maristarchives';
        $ftp_config['password'] = 'archives17';
        $ftp_config['debug']    = TRUE;
        //Connect to the remote server
        if( $this->ftp->connect($ftp_config)){
            if($this -> ftp ->changedir("/Volumes/ArchDrive/Storage/Repository/")) {
                echo "1";
            }
        }

    }
    public function remove_tagAssociation(){
        $tagId = $this->input->get('tid');
        $paperId = $this -> input -> get('paperid');
        $this->load->model('repository_model');
        $result = $this->repository_model -> remove_paperTag($tagId,$paperId);

      if($result == $tagId){
           echo $tagId;
      }else{
            echo 0;
      }
    }
    public function getPapers($dept_id){

        $this->load->model('repository_model');
        // $email = $this->input->get('email');
        //$passcode = $this->input->get('pass');
        //$this->load->model('repository_model');
        //$passcode1 = $this->repository_model->getPasscode(1);
        //$result = $this->repository_model->getAdmin($email);
        //if(($passcode ==$passcode1)) {
          //  foreach ($result as $r){
           //     $dept_id = $r -> dept_id;
           // }
            $query = $this->repository_model->allPapers($this->limit, $dept_id);
            $total_rows = $this->repository_model->count($dept_id);
        $departments = $this->repository_model->getDepartments();

        $this->load->helper('app');
            $pagination_links = pagination($total_rows, $this->limit, $dept_id);
        if($result = $this->repository_model->getDept($dept_id)) {
            foreach ($result as $r ) {
                $department =$r -> name;
                }
                }else{
            $department = "";
        }
            $this->load->view('admin_view', compact('query', 'pagination_links', 'total_rows', 'dept_id', 'department','departments'));
            //}else{
        //}
    }
     //to fetch all the departments.
      public function getdepartments(){
          $this->load->model('repository_model');
          $result = $this -> repository_model -> getDepartments();

          if ($result > 0) {

              $result = json_decode(json_encode($result), true);
              print_r($result);
          }
      }
    //to fetch all the collections.
    public function getCollections(){
        $this->load->model('repository_model');
        $result = $this -> repository_model -> getCollections();

        if ($result > 0) {

            $result = json_decode(json_encode($result), true);
            print_r($result);
        }
    }
/*    public function sendEmail($email_id, $name ,$subject, $message){

        $this->load->library('email');
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "tls://smtp.googlemail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "maristarchives@gmail.com";
        $config['smtp_pass'] = "20MaristArchives15";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $this->email->initialize($config);
        $this->email->from('maristarchives@gmail.com', ' Marist Archives');
        $this->email->to($email_id);
        // $this ->email ->bcc("monish.singh1@marist.edu");
        $this->email->subject('Honors Thesis Paper');

        $emailBody = '<html><body>';

        $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';

        $emailBody .= "<tr ><td align='center'><img src='https://s.graphiq.com/sites/default/files/10/media/images/Marist_College_2_220374.jpg'  /><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honor's Thesis Repository</h3> ";

        $emailBody .= "<br/><br/> <h4>Dear $name,<br/><br/> Your Paper uploaded to our repository successfully. You can access the uploaded paper using below link. </h4><br/> <I>Link:</I><br/><a href='$link'> $link</a> </></br></br><h5></h5> </tr>";

        $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>*Please contact adminstrator if you find any issues.</I></td></tr>";

        //$message .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";
        $emailBody .= "</table>";

        $emailBody .= "</body></html>";

        //  $this->email->cc('dheeraj.karnati1@marist.edu');
        //  $this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');
        $this->email->message($emailBody);

        if(  $this->email->send()){

            return 1;
        }else{
            return 0;
        }

    }*/

    function admin(){

        $sid = SID; //Session ID #

        //if the last session was over 15 minutes ago
        if (isset($_SESSION['LAST_SESSION']) && (time() - $_SESSION['LAST_SESSION'] > 900)) {
            if(!isset($_SESSION['CAS'])) {
                $_SESSION['CAS'] = false; // set the CAS session to false
            }
        }

        $authenticated = $_SESSION['CAS'];
        $casurl = "http%3A%2F%2Flibrary.marist.edu%2Frepository%2F%3Fc%3Drepository%26m%3Dadmin";
      //  $casurl = html_entity_encode($casurl);
        //send user to CAS login if not authenticated
        if (!$authenticated) {
            $_SESSION['LAST_SESSION'] = time(); // update last activity time stamp
            $_SESSION['CAS'] = true;
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas/?service='.$casurl.'">';
            //header("Location: https://cas.iu.edu/cas/login?cassvc=IU&casurl=$casurl");
            exit;
        }

        if ($authenticated) {
            if (isset($_GET["ticket"])) {
                //set up validation URL to ask CAS if ticket is good
                $_url = "https://login.marist.edu/cas/validate";
              //  $serviceurl = "http://localhost:9090/repository-2.0/?c=repository&m=cas_admin";
               // $cassvc = 'IU'; //search kb.indiana.edu for "cas application code" to determine code to use here in place of "appCode"
                   //$ticket = $_GET["ticket"];
                $params = "ticket=$_GET[ticket]&service=$casurl";
                $urlNew = "$_url?$params";

                //CAS sending response on 2 lines. First line contains "yes" or "no". If "yes", second line contains username (otherwise, it is empty).
                $ch = curl_init();
                $timeout = 5; // set to zero for no timeout
                curl_setopt ($ch, CURLOPT_URL, $urlNew);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                ob_start();
                curl_exec($ch);
                curl_close($ch);
                $cas_answer = ob_get_contents();
                ob_end_clean();

                //split CAS answer into access and user
                list($access,$user) = preg_split("/\n/",$cas_answer,2);
                $access = trim($access);
                $user = trim($user);
                //set user and session variable if CAS says YES
                if ($access == "yes") {
                    $_SESSION['user'] = $user;
                    $user= str_replace('@marist.edu',"",$user);
                    $this->load->model('repository_model');
                    $result = $this->repository_model->getAdmin($user);

                    if($result){
                        foreach ($result as $r){
                            $dept_id = $r -> dept_id;

                            }
                            $this->getPapers($dept_id);
                     /*   $query = $this->repository_model->allPapers($this->limit, $dept_id);
                        $total_rows = $this->repository_model->count();
                        $this->load->helper('app');
                        $pagination_links = pagination($total_rows, $this->limit,$dept_id);
                        $this->load->view('admin_view', compact('query', 'pagination_links', 'total_rows','dept_id'));*/
                    }else{

                        echo "<h1>UnAuthorized Access</h1>";
                    }
                }//END SESSION USER
                else{
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas?service='.$casurl.'">';
                }
            } else  {
                echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://login.marist.edu/cas?service='.$casurl.'">';
            }
        }
    }

	}

?>
