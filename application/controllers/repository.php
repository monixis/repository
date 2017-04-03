<?phpclass repository extends CI_Controller{    public function __construct()    {        parent::__construct();    }    private $limit = 10;    public function index()    {        $date = date_default_timezone_set('US/Eastern');        $this->load->model('repository_model');        $data['keywords'] = $this->repository_model->getKeywords();        $this->load->view('search', $data);    }    /*    *Function to uploaded_papers thesis documents.    *    */	 public function ack(){	 	$this->load->view('ack');	 }   public function insertDetails()    {        date_default_timezone_set('US/Eastern');        $date = date("m/d/Y");        $this->load->model('repository_model');        $tags = json_decode($_POST['tags']);        //   $is_tagsMapped = false;        $tagstoMap = array();        if (sizeof($tags) > 0) {            foreach ($tags as $tag) {                $tagid = $this->repository_model->verifyTag($tag);                if ($tagid == 0) {                    $new_tagId = $this->repository_model->addATag($tag);                    if ($new_tagId > 0) {                        array_push($tagstoMap, $new_tagId);                        //  $tagstoMap.add($new_tagId);                    }                } else {                    // $tagstoMap.add($tagid);                    array_push($tagstoMap, $tagid);                }            }        }        $status = 1; // submitted by author        $paperId = mt_rand(100000, 999999);        // $this->generatePaperId();        $paperId = $this->repository_model->insertDetails($paperId, $_POST['name'], $_POST['cwid'], $_POST['title'], $_POST['email'], $date, $status, $_POST['abstract'], $_POST['licenseId'], $_POST['year']);        if ($paperId > 0) {            if (sizeof($tagstoMap) > 0) {                foreach ($tagstoMap as $tagtoMap) {                    $result = $this->repository_model->mapTag($paperId, $tagtoMap);                    if ($result != 1) {                        echo 0;                    }                }            }            if ($result == 1) {                    echo $paperId;                } else {                    echo 0;                }            }            else {                echo $paperId;            }    }    public function generatePaperId()    {        $this->load->model('repository_model');        $id = mt_rand(10000, 99999);        $paperId = $this->repository_model->checkPaperId($id);        if ($paperId) {            $id = mt_rand(10000, 99999);            echo $id;        } else {            $id = mt_rand(10000, 99999);            $paperId = $this->repository_model->checkPaperId($id);            if ($paperId) {                $id = mt_rand(10000, 99999);                echo $id;            }        }    }    /* public function loadTags()    {        $this->load->model('repository_model');        $data['tags'] = $this->repository_model->getTags();        $this->load->view('tag', $data);    }    /*public function loadKeywords()    {        $this->load->model('repository_model');        $data['keywords'] = $this->repository_model->getKeywords();        $this->load->view('keywords', $data);    }*/    public function addATag()    {        /*$tagname = $_POST['tagname'];        $this->load->model('repository_model');        $result = $this->repository_model->addATag($tagname);        echo $result;*/        $tagname = $_POST['tagname'];        $this->load->model('repository_model');        $tagid = $this->repository_model->verifyTag($tagname);        //echo $tagid;        if ($tagid == 0) {            $result = $this->repository_model->addATag($tagname);            echo $result;        } else {            echo $tagid;        }    }    public function upload()    {        $this->load->model('repository_model');        //$data['keywords'] = $this->repository_model->getKeywords();        $this->load->view('repository');    }    public function searchByTag()    {        $this->load->model('repository_model');        $q = $this->input->get('q');        $data['result'] = $this->repository_model->searchByTag($q);        //  $data['result1'] = $this ->repository_model -> searchByName($q);        $this->load->view('results', $data);    }    public function searchResultsByTag()    {        $this->load->model('repository_model');        $data['searchString'] = "";        $data['keywords'] = $this->repository_model->getKeywords();        $data['searchString'] = $this->input->get('q');        $this->load->view('search', $data);    }    public function searchResultsByKeyWord()    {        $this->load->model('repository_model');        $data['searchString'] = "";        $data['keywords'] = $this->repository_model->getKeywords();        $data['searchString'] = $this->input->get('q');        $this->load->view('search', $data);    }    public function testquery()    {        $this->load->model('repository_model');        $q = $this->input->get('q');        $keywordType = "";        $keywordResult = $this->repository_model->searchKeywords($q);        if ($keywordResult) {            $keywordResult = json_decode(json_encode($keywordResult), true);            foreach ($keywordResult as $keyword) {                $keywordType = $keyword['type'];            }            echo $keywordType;        }    }    public function searchKeyWords()    {        $this->load->model('repository_model');        $q = $this->input->get('q');        $keywordType = "";        $keywordResult = $this->repository_model->searchKeywords($q);        if ($keywordResult) {            $keywordResult = json_decode(json_encode($keywordResult), true);            foreach ($keywordResult as $keyword) {                $keywordType = $keyword['type'];            }        }        if ($keywordType == "t") {            $data['result'] = $this->repository_model->searchByTag($q);            $this->load->view('results', $data);        } else if ($keywordType == "ti") {            $data['result'] = $this->repository_model->searchByPaper($q);            $this->load->view('results', $data);        } else if ($keywordType == "n") {            $data['result'] = $this->repository_model->searchByName($q);            $this->load->view('results', $data);        } else {            $data['result'] = $this->repository_model->searchByPaper($q);            $this->load->view('results', $data);        }        //if($keywordResult[0]== 't') {        //}    }    /*   * Email user with $link   * required $user_name,$email_id,$link   *  */    public function email_user()    {        $this->load->library('email');        $this->load->model('repository_model');        $config['protocol'] = "smtp";        $config['smtp_host'] = "tls://smtp.googlemail.com";        $config['smtp_port'] = "465";        $config['smtp_user'] = "maristarchives@gmail.com";        $config['smtp_pass'] = "MaristArchives2017";        $config['charset'] = "utf-8";        $config['mailtype'] = "html";        $config['newline'] = "\r\n";        $this->email->initialize($config);        $name = $_POST["name"];        $email_id = $_POST["email"];        $paperId = $_POST['paperid'];        $this->email->from('maristarchives@gmail.com', ' Marist Archives');        $this->email->to($email_id);        $file_info = pathinfo($_FILES['file_attach']['name']);        $url = "http://148.100.181.189:8090/repository/uploaded/".$paperId.".".$file_info['extension'];        $res = $this->repository_model->updateLink($url, $paperId);        if($res) {            $link = "http://library.marist.edu/repository?c=repository&m=fileInfo&id=" . $paperId;            // $this ->email ->bcc("monish.singh1@marist.edu");            $this->email->subject('Honors Thesis Paper');            $emailBody = '<html><body>';            $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';            $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";            $emailBody .= "<h4>Dear $name,<br/><br/> Your Paper has been uploaded successfully and is awaiting approval. You will be notified once the paper is approved. </h4></br></tr>";            $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>*Please contact adminstrator if you find any issues.</I></td></tr>";            //$message .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";            $emailBody .= "</table>";            $emailBody .= "</body></html>";            //  $this->email->cc('dheeraj.karnati1@marist.edu');            //  $this->email->cc('another@another-example.com');            //$this->email->bcc('them@their-example.com');            $this->email->message($emailBody);            if ($this->email->send()) {                echo $paperId;            } else {                return 0;            }        }else{            return 0;        }    }    public function email_resub($name, $link, $email_id)    {        $this->load->library('email');        $config['protocol'] = "smtp";        $config['smtp_host'] = "tls://smtp.googlemail.com";        $config['smtp_port'] = "465";        $config['smtp_user'] = "maristarchives@gmail.com";        $config['smtp_pass'] = "MaristArchives2017";        $config['charset'] = "utf-8";        $config['mailtype'] = "html";        $config['newline'] = "\r\n";        $this->email->initialize($config);        $this->email->from('maristarchives@gmail.com', ' Marist Archives');        $this->email->to('maristarchives@gmail.com');        // $this ->email ->bcc("monish.singh1@marist.edu");        $this->email->subject('Honors Thesis Paper');        $emailBody = '<html><body>';        $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';        $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";        $emailBody .= "<br/><br/> <h4>$name has been resubmitted paper. Please click on the following link to review the paper.</h4><br/> <I>Link:</I><br/><a href='$link'> $link</a> </></br></br><h5></h5> <</tr>";        $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I></I></td></tr>";        //$message .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";        $emailBody .= "</table>";        $emailBody .= "</body></html>";        //  $this->email->cc('dheeraj.karnati1@marist.edu');        //  $this->email->cc('another@another-example.com');        //$this->email->bcc('them@their-example.com');        $this->email->message($emailBody);        if ($this->email->send()) {            return 1;        } else {            return 0;        }    }    public function fileInfo()    {        $this->load->model('repository_model');        $id = $this->input->get('id');        $paperinfo = $this->repository_model->getPaperDetails($id);        if ($paperinfo) {            $paperinfo_dec = json_decode(json_encode($paperinfo), true);            foreach ($paperinfo_dec as $paper) {                $status = $paper['status'];                $paper['paperid'];            }            $associatedTags = $this->repository_model->getPaperTags($id);            if ($status != 3) {                $data['paperinfo'] = $paperinfo_dec;                $data['associatedTags'] = json_decode(json_encode($associatedTags), true);                $this->load->view('file_view', $data);            } else {                $data['paperinfo'] = $paperinfo_dec;                $data['associatedTags'] = json_decode(json_encode($associatedTags), true);                $this->load->view('resubmit', $data);            }        } else {            $this->load->view('pagenotfound');        }    }    public function view_file()    {        $this->load->model('repository_model');        $id = $this->input->get('id');        $paperinfo = $this->repository_model->getPaperDetails($id);        if ($paperinfo) {            $paperinfo_dec = json_decode(json_encode($paperinfo), true);            foreach ($paperinfo_dec as $paper) {                $status = $paper['status'];                $paper['paperid'];            }            $associatedTags = $this->repository_model->getPaperTags($id);            $data['paperinfo'] = $paperinfo_dec;            $data['status'] = $status;            $data['associatedTags'] = json_decode(json_encode($associatedTags), true);            $this->load->view('file', $data);        } else {            $this->load->view('pagenotfound');        }    }    public function getHistory()    {        $this->load->model('repository_model');        $id = $this->input->get('id');        $history_array = $this->repository_model->getHistory($id);        if ($history_array > 0) {            $history = json_decode(json_encode($history_array), true);            print_r($history);        }    }    public function reviewPaper()    {        $this->load->model('repository_model');        $id = $this->input->get('id');        $paperinfo = $this->repository_model->getPaperDetails($id);        if ($paperinfo) {            $data['paperinfo'] = json_decode(json_encode($paperinfo), true);            $associatedTags = $this->repository_model->getPaperTags($id);            $history_array = $this->repository_model->getHistory($id);            if ($history_array > 0) {                $history = json_decode(json_encode($history_array), true);                $data['history'] = $history;            }            $data['associatedTags'] = json_decode(json_encode($associatedTags), true);            $this->load->view('reviewpaper', $data);        } else {            $this->load->view('pagenotfound');        }    }    public function substring()    {        $id = $this->input->get('id');        $paperid = substr($id, -1);        $id = substr($id, 0, 5);        echo $id . $paperid;    }    public function approvePaper()    {        $id = $this->input->get('id');        $name = $_POST['name'];        $email = $_POST['email'];        $this->load->model('repository_model');        $comments = $_POST['comments'];        $status = 2;        $result = $this->repository_model->updatePaperStatus($id, $status);        $url = "http://library.marist.edu/repository?c=repository&m=view_file&id=".$id;        if ($result == 1) {            $this->load->library('email');            $config['protocol'] = "smtp";            $config['smtp_host'] = "tls://smtp.googlemail.com";            $config['smtp_port'] = "465";            $config['smtp_user'] = "maristarchives@gmail.com";            $config['smtp_pass'] = "MaristArchives2017";            $config['charset'] = "utf-8";            $config['mailtype'] = "html";            $config['newline'] = "\r\n";            $this->email->initialize($config);            $this->email->from('maristarchives@gmail.com', ' Marist Archives');            $this->email->to($email);            // $this ->email ->bcc("monish.singh1@marist.edu");            $this->email->subject('Honors Thesis Paper');            /***************************************************************************************************/            $emailBody = '<html><body>';            $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';            $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";            $emailBody .= "<br/><br/> <h4>Dear $name,<br/><br/> Your paper has been approved successfully and available to search.</h4><br/></tr>";            $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";            $emailBody .=  "<tr><td colspan=2 font='colr:#3A5896;'><I>Comments:<br></I><h4>$comments</h4></td></tr>";            $emailBody .= "</table>";            $emailBody .= "</body></html>";            /***************************************************************************************************/            $this->email->message($emailBody);            if ($this->email->send()) {                echo 1;            } else {                echo 0;            }        } else {            echo 0;        }    }    public function returnPaper()    {        $id = $this->input->get('id');        $name = $_POST['name'];        $email = $_POST['email'];        $comments = $_POST['comments'];        $date = date("m/d/Y");        $this->load->model('repository_model');        $status = 3;        $result = $this->repository_model->updatePaperStatus($id, $status);        if ($result == 1) {            $message = $this->repository_model->saveToHistory($id, $status, $comments, $date);            if ($message == 1) {                $this->load->library('email');                $config['protocol'] = "smtp";                $config['smtp_host'] = "tls://smtp.googlemail.com";                $config['smtp_port'] = "465";                $config['smtp_user'] = "maristarchives@gmail.com";                $config['smtp_pass'] = "MaristArchives2017";                $config['charset'] = "utf-8";                $config['mailtype'] = "html";                $config['newline'] = "\r\n";                $this->email->initialize($config);                $this->email->from('maristarchives@gmail.com', ' Marist Archives');                $this->email->to($email);                $url = "http://library.marist.edu/repository?c=repository&m=upload";                // $this ->email ->bcc("monish.singh1@marist.edu");                $this->email->subject('Honors Thesis Paper');                /***************************************************************************************************/                $emailBody = '<html><body>';                $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';                $emailBody .= "<tr ><td align='center'><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honors Thesis Repository</h3> ";                $emailBody .= "<br/><br/> <h4>Dear $name,<br/><br/> Your paper has been returned. Please resubmit the paper using below link .</h4><br/></td></tr>";                $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";                $emailBody .=  "<tr><td colspan=2 font='colr:#3A5896;'><I>Comments:<br></I><h4>$comments</h4></td></tr>";                $emailBody .= "</table>";                $emailBody .= "</body></html>";                /***************************************************************************************************/                $this->email->message($emailBody);                if ($this->email->send()) {                    echo 1;                } else {                    echo 0;                }            } else {                echo 0;            }        } else {            echo 0;        }    }    public function test()    {        $this->load->model('repository_model');        $query = $this->repository_model->allPapers($this->limit);        print_r($query);    }    public function admin()    {        $this->load->model('repository_model');        $query = $this->repository_model->allPapers($this->limit);        $total_rows = $this->repository_model->count();        $this->load->helper('app');        $pagination_links = pagination($total_rows, $this->limit);        $this->load->view('admin_view', compact('query', 'pagination_links', 'total_rows'));    }    public function admin_verify()    {        $apasscode = $this->input->get('pass');        $this->load->model('repository_model');        $passcode = $this->repository_model->getPasscode(1);        if ($passcode == $apasscode) {            $authorized = 1;        } else {            $authorized = 0;        }        echo $authorized;    }    public function pages()    {        $this->load->model('repository_model');        $query = $this->repository_model->allPapers($this->limit);        $total_rows = $this->repository_model->count();        $this->load->helper('app');        $pagination_links = pagination($total_rows, $this->limit);        $this->load->view('page_view', compact('query', 'pagination_links', 'total_rows'));    }    public function papers()    {        $status = $this->input->get('status');        if ($status != null) {            $url = base_url("?c=repository&m=papers&status=$status");        } else {            $url = null;        }        $this->load->model('repository_model');        $query = $this->repository_model->papersWithStatus($this->limit, $status);        $total_rows = $this->repository_model->countWithStatus($status);        $this->load->helper('status');        $pagination_links = pagination($total_rows, $this->limit, $url);        $this->load->view('page_view', compact('query', 'pagination_links', 'total_rows'));    }    public function updateDetails()    {        $this->load->model('repository_model');        date_default_timezone_set('US/Eastern');        $date = date("m/d/Y");        $tags = json_decode($_POST['tags']);        $tagstoMap = array();        if (sizeof($tags) > 0) {            foreach ($tags as $tag) {                $tagid = $this->repository_model->verifyTag($tag);                if ($tagid == 0) {                    $new_tagId = $this->repository_model->addATag($tag);                    if ($new_tagId > 0) {                        array_push($tagstoMap, $new_tagId);                        //  $tagstoMap.add($new_tagId);                    }                } else {                    // $tagstoMap.add($tagid);                    array_push($tagstoMap, $tagid);                    //echo $tagid;                }            }        }        $paperId = $this->input->get('id');        $status = 1;        $paperId = $this->repository_model->updateDetails($paperId, $_POST['title'], $date, $status, $_POST['abstract']);        if ($paperId > 0) {            if (sizeof($tagstoMap) > 0) {                foreach ($tagstoMap as $tagtoMap) {                    $result = $this->repository_model->mapTag($paperId, $tagtoMap);                    if ($result != 1) {                        echo 0;                    }                }            }        }        if ($result == 1) {            //echo $paperId;            $file_name = $_FILES['file_attach']['name'];            $file_tmp_name = $_FILES['file_attach']['tmp_name'];            $file_error = $_FILES['file_attach']['error'];            // $file_size = $_FILES['file_attach']['size'];            // $file_type = $_FILES['file_attach']['type'];            // if($file_size<30000000) {            //exit script and output error if we encounter any            if ($file_error > 0) {                $errormsg = array(                    1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",                    2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",                    3 => "The uploaded file was only partially uploaded",                    4 => "No file was uploaded",                    6 => "Missing a temporary folder");                $output = json_encode(array('type' => 'error', 'text' => $errormsg[$file_error]));                die($output);            }            $file_attached = true;            if ($file_attached) {                // *********** Uncomment below line in server *********** //                   $ds = "/data/library/htdocs/repository/";                // ********** Comment below line in server ************  //               // $ds = "/Applications/MAMP/htdocs/repository/";                $storeFolder = 'uploaded_papers/';//2                if (!empty($_FILES)) {                    $targetPath = $ds . $storeFolder;  //4                    // $targetFile =  $targetPath. $_FILES['file_attach']['name'];  //5                    $file_info = pathinfo($file_name);                    $pid = $paperId;                    $filename = $pid ."_resubmission". '.' . $file_info['extension']; //renaming the file                    $targetFile = $targetPath . $filename;                    // echo $targetFile;                    // $result = $this->usragr_model->update_attachment($filename, $userId);                    // *********** Uncomment below lines in server ********* //                      $filepath = "http://library.marist.edu/repository/uploaded_papers/$filename";                      $link = "http://library.marist.edu/repository?c=repository&m=fileInfo&id=".$paperId;                    // *********** Comment below lines in server *********  //                   // $filepath = "http://localhost:9090/repository/uploaded_papers/$filename";                   // $link = "http://localhost:9090/repository/?c=repository&m=fileInfo&id=" . "$pid";                    /*********************************************************/                    $fileupload = move_uploaded_file($file_tmp_name, $targetFile); //uploaded_papers the renamed file to target directory                    if ($fileupload) {                        $res = $this->repository_model->updateLink($filepath, $paperId);                        if ($res == 1) {                            $emailres = $this->email_user($_POST['name'], $link, $_POST['email']);                            if ($emailres == 1) {                                echo $paperId;                            }                        }                    }                }            }        } else {            echo 0;        }        {}    }    public function ftpp(){        $this->load->library('ftp');       	$ftp_config['hostname'] = 'http://148.100.181.189';        //  $ftp_config['port'] = 8080;        $ftp_config['username'] = 'maristarchives';        $ftp_config['password'] = 'archives17';        $ftp_config['debug']    = TRUE;        //Connect to the remote server        if( $this->ftp->connect($ftp_config)){            if($this -> ftp ->changedir("/Volumes/ArchDrive/Storage/Repository/")) {                echo "1";            }        }    }    public function getPapers(){        $this->load->model('repository_model');        $query = $this -> repository_model -> allPapers($this-> limit);        $total_rows = $this -> repository_model ->count();        $this -> load -> helper('app');        $pagination_links = pagination($total_rows,$this->limit);        $this -> load -> view('admin', compact('query','pagination_links','total_rows'));    }/*    public function sendEmail($email_id, $name ,$subject, $message){        $this->load->library('email');        $config['protocol'] = "smtp";        $config['smtp_host'] = "tls://smtp.googlemail.com";        $config['smtp_port'] = "465";        $config['smtp_user'] = "maristarchives@gmail.com";        $config['smtp_pass'] = "20MaristArchives15";        $config['charset'] = "utf-8";        $config['mailtype'] = "html";        $config['newline'] = "\r\n";        $this->email->initialize($config);        $this->email->from('maristarchives@gmail.com', ' Marist Archives');        $this->email->to($email_id);        // $this ->email ->bcc("monish.singh1@marist.edu");        $this->email->subject('Honors Thesis Paper');        $emailBody = '<html><body>';        $emailBody .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';        $emailBody .= "<tr ><td align='center'><img src='https://s.graphiq.com/sites/default/files/10/media/images/Marist_College_2_220374.jpg'  /><h3>James A. Cannavino Library, Marist College</h3> <br/><h3>Honor's Thesis Repository</h3> ";        $emailBody .= "<br/><br/> <h4>Dear $name,<br/><br/> Your Paper uploaded to our repository successfully. You can access the uploaded paper using below link. </h4><br/> <I>Link:</I><br/><a href='$link'> $link</a> </></br></br><h5></h5> </tr>";        $emailBody .= "<tr><td colspan=2 font='colr:#3A5896;'><I>*Please contact adminstrator if you find any issues.</I></td></tr>";        //$message .= "<tr><td colspan=2 font='colr:#3A5896;'><I>Link:<br></I> $url </td></tr>";        $emailBody .= "</table>";        $emailBody .= "</body></html>";        //  $this->email->cc('dheeraj.karnati1@marist.edu');        //  $this->email->cc('another@another-example.com');        //$this->email->bcc('them@their-example.com');        $this->email->message($emailBody);        if(  $this->email->send()){            return 1;        }else{            return 0;        }    }*/}?>