<?phpclass repository_model extends CI_Model{    function __construct()    {        // Call the Model constructor        parent::__construct();        $this->load->database();    }		 /*     * Insert details of the paper and author     */    public function insertDetails($title, $name, $cwid, $date)    {        $sql = "INSERT INTO repository(name, cwid, title, uploaddate) Values ('$title', '$name', '$cwid', '$date');";		$this->db->simple_query($sql, array($title, $name, $cwid, $date));		if ($this->db->affected_rows() > 0) {			return 1;		} else {			return 0;		}    }		public function getTags()	{		$sql = "SELECT tid, tag FROM tags";		$results = $this->db->query($sql);		return $results->result();	}			 public function addATag($tagname)    {        $sql = "INSERT INTO tags(tag) Values ('$tagname');";		$this->db->simple_query($sql, array($tagname));		if ($this->db->affected_rows() > 0) {			return 1;		} else {			return 0;		}    }		 public function verifyTag($tagname)    {	$tagid = 0;		$this->db->select('tid');		$this->db->from('tags');		$this->db->where('tag', $tagname);		$query = $this->db->get();		foreach ($query->result() as $row) {			$tagid = $row->tid;		}		return $tagid;    }}	?>