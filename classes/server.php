<?php
//$db = new Mysqlidb("mysql-env-4315189.jelastic.regruhosting.ru", "root", "0BdPMn3QJI", "test");
$db = new Mysqlidb('localhost', 'root', '', 'readspeed');
class Lib
{
	static public function cutAnsw($str)
	{
		$str = preg_replace("/[^0-9]/", '', $str);
		return $str;
	}
	static function Debug($array)
	{
		echo "<pre>";
		print_r($array);
		echo "</pre>";
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
		/* Объект подключения к базе  */
		$db = MysqliDb::getInstance();
		$this->db = $db;
		$texts = $this->getText();

		if ($right_texts = $this->check_text($texts, $user->old)) {				// Возвращает массив текстов с десятью вопросами в базе и не являющихся прочитанными
			/* рандомный текст */
			$rnd_text = $this->rnd($right_texts);
			/************************/
			$this->id = $rnd_text['id'];
			$this->text = $rnd_text['text'];
			$this->name = $rnd_text['text_name'];
			/************************/
			$this->lenght = $this->get_lenght($rnd_text['text']);
		}
		else{
			$this->sorry = "Извините, нет доступных текстов, вы прочли их все. Заходите позже :)";
		}
	}
	private function rnd($right_texts)
	{
		return $right_texts[array_rand($right_texts)];
	}
	function check_text($texts, $old = array())
	{
		$right_count = 10;
		foreach ($texts as $k => $v) {
			if (($v['count'] == $right_count) && !$this->isOld($v['id'], $old))
				$right[] = $v;
		}
		if($right)
			return $right;
		else
			return FALSE;

	}
	function getText()
	{
		// Сложный вложеный запрос, сложный и вложеный, бичес!
		return $this->db->rawQuery("SELECT * , (SELECT COUNT(*) FROM questions WHERE questions.id_text = text.id) as count FROM text");
	}
	function get_lenght($text)
	{
		$text = preg_replace("/\s.{0,3}\s/", "", $text);		// Убираем слова короче трех символов	
		$text =  preg_replace('/[^\w\s]/u', ' ', $text);		// Знаки припенания
		return mb_strlen( $text, 'UTF-8' );
	}
	function isOld($tid,$old)
	{
		if (!empty($old)) {
			foreach ($old as $key) {
				if($key['tid'] == $tid)
					return TRUE;
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

	function __construct($id_text)
	{
		$db = MysqliDb::getInstance();
		$this->db = $db;

		$db->where('id_text',$id_text);
		$this->questions = $db->get('questions');
	}
	function getQ($i)
	{
		return $this->q($this->questions[$i]);
	}
	function q($q)
	{
		$this->id = $q['id'];
		$this->id_text = $q['id_text'];
		$this->text_question = $q['text_question'];
		$this->answer = $q['answer'];
		return TRUE;
	}
	function getQid($id)
	{
		if ($qk = array_search($id, $this->questions))
			return $this->q($this->questions[$qk]);
		else
			return FALSE;
	}
}
class Option
{
	public $opt;												// all options @array
	public $id;
	public $id_question;
	public $text_option;
	function __construct($id_question)
	{
		$db = MysqliDb::getInstance();
		$db->where('id_question',$id_question);
		$options = $db->get('options');
		$this->opt = Lib::shuffle_assoc($options);
	}
}
class Read
{
	public $bt;													// begin time
	public $et;													// end time
	public $rt;													// read time
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
	public $a;													//answer
	public $ra;													//right answer
	public $qs;													//questions
	public $speed;
	function __construct(Question $qs)
	{
		$this->qs = $qs;
	}
	function checkAnsw($array)
	{
		$q = $this->qs->questions;
		foreach ($array as $key => $value) {
			$aid = Lib::cutAnsw($value);							// id ответ
			if($qid = $this->search($q, $aid))
				$this->ra[$qid] = $aid;
		}
	}
	function search($array, $aid)
	{
		$i=0;
		do
		{
			if($array[$i]['answer'] == $aid)
				return $array[$i]['id'];
		} while(++$i<count($array));
	}
	function addAnsw($qi, $ai)									//qi-ид вопроса ; ai-ид ответа
	{

	}
	function result($x, $t, $c)
	{
			$this->speed = round(($x/$t)*$c);
			if ($this->speed)
				return $this->speed;
			else
				return FALSE;
	}
}
class User
{
	private $db;
	public $id;
	private $u;													// user @array
	private $uid;		
	private $tid;												// text id
	private $reg_time;		
	public $old; 												// readed text
	function __construct($uid)									// uid приходит как GET-параметр viewer_id если приложение запущенно вконтакте
	{		
		/* Объект подключения к базе  */	
		$this->db = MysqliDb::getInstance();	
	
		if ($this->u = $this->getUser($uid))					// если пользователь с этим uid существует в базе getUser(#) 
			$this->old = $this->getOld($this->u['id']);					// получаем прочитанное ранее getOld(#) возвр @array										
		else		
			$this->u = $this->setUser($uid);					// иначе создаем нового пользователя setUser(#) возвращает @array

		$this->id = $this->u['id'];
		$this->uid = $this->u['uid'];
		$this->reg_time = $this->u['time'];
	}
	function setTid($tid)
	{
		$this->tid = $tid;
		return TRUE;
	}
	private function getOld($id)
	{
		$db = $this->db;
		$db->where('uid',$id);
		$old = $db->get('old');

		if (!is_null($old))
			return $old;
		else
			return FALSE;
	}
	private function getUser($uid)
	{
		$db = $this->db;
		$db->where('uid',$uid);
		$user = $db->getOne('users');

		if (!is_null($user))
			return $user;
		else
			return FALSE;
	}
	private function setUser($uid)
	{
		$data = array(
					'uid' => $uid,
					'time' => time(),
					);
		$id = $this->db->insert('users', $data);
		if ($id)
			return $this->getUser($uid);
		else
			return FALSE;

	}
	function setOld($rt,$cu,$speed)
	{
		if($rt && $speed)
		{
			$data = array(
						'uid' => $this->id,
						'tid' => $this->tid,						// text_id
						'rt' => $rt,								// read_time
						'cu' => $cu,								// coeffitient_understanding
						'speed' => $speed,
						'time' => time(),
						);
			$id = $this->db->insert('old', $data);
			//print_r($data);
			if($id)
				return $id;
			else
				return FALSE;
		}
	}
}
class apiVk
{
	public $client_id = '4295493';									// id приложения
	public $client_secret = 'nDq5yRKpfSjvqcu9Dc0F';					// секретный ключ
	public $token;													// сюда токен доступа
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
