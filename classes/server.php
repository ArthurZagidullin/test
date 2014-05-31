<?php
class DB
{
	static public $db;
	static public function get_instance()
	{
		if(!empty(self::$db))
		{
			return self::$db;
		}
		//self::$db = new mysqli("mysql-env-4315189.jelastic.regruhosting.ru", "root", "0BdPMn3QJI", "test");
		self::$db = new mysqli("localhost", "root", "", "readspeed");
		self::$db->set_charset("utf8");
		if (self::$db->connect_errno) {
		    echo "Не удалось подключиться к MySQL: " . self::$db->connect_error;
		}
		return self::$db;
	}
	function __construct(){}
}
class Lib
{
	static public function cutAnsw($str)
	{
		$str = preg_replace("/[^0-9]/", '', $str);
		return $str;
	}
	static function shuffle_assoc($list) { 
	  if (!is_array($list)) return $list; 

	  $keys = array_keys($list); 
	  shuffle($keys); 
	  $random = array(); 
	  foreach ($keys as $key) 
	    $random[$key] = $list[$key]; 

	  return $random; 
	} 
}
class Text
{
	private $db;
	private $all_texts;
	public $right_texts;
	public $id;
	public $name;
	public $text;
	public $lenght;
	public $sorry;
	function __construct(User $user)
	{
		/* Получаем все тексты из базы */
		$this->db = DB::get_instance();
		$result = $this->db->query("SELECT * FROM text");
		$this->all_texts = $result->fetch_all();
		/*******************************/
		if ($this->right_texts = $this->check_text($this->all_texts, $user->old)) {	// <== полный ппц
			/* рандомный текст */
			$rnd_text = $this->rnd();
			/************************/
			$this->id = $rnd_text[0];
			$this->text = $rnd_text[1];
			$this->name = $rnd_text[2];
			/********************/
			$this->lenght = $this->get_lenght($this->text);
		}
		else{
			$this->sorry = "Извините, нет доступных текстов, вы прочли их все. Заходите позже :)";
		}
	}
	private function rnd()
	{
		return  $this->right_texts[array_rand($this->right_texts)];
	}
	function check_text($all_texts, $old = array())
	{
		$right_count = 10;				// количество вопросов которое должно быть у текста
		foreach ($all_texts as $key => $value) {
			$row = $this->db->query("SELECT COUNT(id) FROM questions WHERE id_text = '".$value[0]."'")->fetch_all();
			$ct = $row[0][0]; 			// эта хрень количество вопросов, достаем из массива $row
			if ($ct == $right_count && !$this->isOld($value[0], $old)) {
				$result[] = $value;
			}
		}
		return $result;
	}
	function get_lenght($text)
	{
		// Убираем слова короче трех символов
		$text = preg_replace("/\s.{0,3}\s/", "", $text);
           
		// Знаки припенания
		$text =  preg_replace('/[^\w\s]/u', ' ', $text);
        
		return mb_strlen( $text, 'UTF-8' );
	}
	function isOld($tid,$old)
	{
		//var_dump($old);
		if (!empty($old)) {
			foreach ($old as $key) {
				if(array_search($tid, $key)){
					return TRUE;
				}
			}
		}
		return FALSE;	
	}
}
class Question
{
	public $questions;
	public $id;
	public $text_question;
	public $id_text;
	public $answer;

	function __construct($text_id)
	{
		$db = DB::get_instance();
		$this->questions = $db->query("SELECT * FROM questions WHERE id_text = '".$text_id."'")->fetch_all();
	}
	function getQ($i)
	{
		$this->q($this->questions[$i]);
	}
	function q($q)
	{
		$this->id = $q[0];
		$this->id_text = $q[1];
		$this->text_question = $q[2];
		$this->answer = $q[3];
	}
	function getQid($id)
	{
		if ($qk = array_search($id, $this->questions)) {
			$this->q($this->questions[$qk]);
			//var_dump($this->questions);
		}
	}
}
class Option
{
	public $opt;	// all options @array
	public $id;
	public $id_question;
	public $text_option;
	function __construct($id_question)
	{
		$db = DB::get_instance();
		$result = $db->query("SELECT * FROM options WHERE id_question = '".$id_question."'")->fetch_all();
		$this->opt = Lib::shuffle_assoc($result);
	}
}
class Read
{
	public $bt; // begin time
	public $et; // end time
	public $rt; // read time
	function begin()
	{
		$this->bt = time();
		return $this->bt;
	}
	function end()
	{
		$this->et = time();
		$this->rt = $this->et - $this->bt;
	}
}
class Quiz
{
	public $a;	//answer
	public $ra;	//right answer
	public $qs;	//questions
	public $speed;
	function __construct(Question $qs)
	{
		$this->qs = $qs;
	}
	function addAnsw($qi, $ai)	//qi-ид вопроса ; ai-ид ответа
	{
		$qs = $this->qs->questions;
		foreach ($qs as $k => $v) {
			if ($v[3] == $ai) {
				$this->ra[$qi] = $ai;
			}
		}
		$this->a[$qi] = $ai;
	}
	function result($x, $t, $c)
	{
		$this->speed = round(($x/$t)*$c);
		return $this->speed;
	}
}
class User
{
	public $id;
	public $u;											// user @array
	public $uid;	
	public $tid;										// text id
	public $reg_time;	
	public $old; 										// readed text
	function __construct($uid)							// uid приходит как GET-параметр viewer_id если приложение запущенно вконтакте
	{	
			
		if ($this->u = $this->getUser($uid)) {			// если пользователь с этим uid существует в базе getUser(#) 
			$this->old = $this->getOld($this->u[0]);			// получаем прочитанное ранее getOld(#) возвр @array
		}														
		else{	
			$this->u = $this->setUser($uid);			// иначе создаем нового пользователя setUser(#) возвращает @array
		}
		$this->id = $this->u[0];
		$this->uid = $this->u[1];
		$this->reg_time = $this->u[2];
	}
	private function getOld($id)
	{
		//var_dump($id); echo "<br>";
		$result = DB::get_instance()->query("SELECT * FROM old WHERE uid = ".$id);
		$result = $result->fetch_all();
		//var_dump(count($result)); echo "<br>";
		if (!empty($result)) {
			return $result;
		}
		return FALSE;
	}
	private function getUser($uid)
	{
		$result = DB::get_instance()->query("SELECT * FROM users WHERE uid = '".$uid."'");
		$result = $result->fetch_all();

		if (count($result)) {
			return $result[0];
		}
		return FALSE;
	}
	private function setUser($uid)
	{
		$t = time();
		$res = DB::get_instance()->query("INSERT INTO users (uid, time) VALUES ('".$uid."', '".$t."')");
		if ($res) {
			return $this->getUser($uid);
		}
	}
	function setOld($rt,$cu,$speed)
	{
		//tid -- text id; cu -- коэффициент понимания; rt -- read time
		if($rt && $cu && $speed)
		{
			$time = time();
			//var_dump($rt,$cu);
			DB::get_instance()->query("INSERT INTO old (uid, tid, rt, cu, speed, time) VALUES (".$this->id.",".$this->tid.",".$rt.",".$cu.",".$speed.",".$time.")");
		}
		/*
			echo "Чето нехватает: ";
			var_dump($rt,$cu,$speed);
		*/
	}
}
class apiVk
{
	public $client_id = '4295493';						// id приложения
	public $client_secret = 'nDq5yRKpfSjvqcu9Dc0F';		// секретный ключ
	public $token;										// сюда токен доступа
	function __construct()
	{
		$url = "https://oauth.vk.com/access_token?client_id=4295493&client_secret=nDq5yRKpfSjvqcu9Dc0F&v=5.21&grant_type=client_credentials";
		$resp = file_get_contents($url);
		$data = json_decode($resp, true);
		$this->resp = $data;
	}
	function getUser()
	{
		$url = 'https://api.vk.com/method/users.get?&v=5.21&access_token='.$this->token;
	}
}
