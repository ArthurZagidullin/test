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
	private $user;
	function __construct(User $user)
	{
		$this->db = DB::get_instance();
		$this->user = $user;
		$this->all_texts = $this->db->query("SELECT * FROM text")->fetch_all();	// Все тексты из базы
		/* рандомный текст */
		$rnd_text = $this->rnd();
		$this->id = $rnd_text[0];
		$this->text = $rnd_text[1];
		$this->name = $rnd_text[2];
		/********************/
		$this->lenght = $this->get_lenght($this->text);
	}
	private function rnd()
	{
		$this->check_text();
		return  $this->right_texts[array_rand($this->right_texts)];
	}
	function check_text()
	{
		$right_count = 10;				// количество вопросов которое должно быть у текста
		/*$qs = new Question($id);		
		$ct = count($qs->questions);	// считаем сколько вопросов у текста
		unset($qs);						
		if ($right_count == $ct) {		// если вопросов достаточно вернем TRUE
			return TRUE;
		}
		return FALSE;*/
		foreach ($this->all_texts as $key => $value) {
			$row = $this->db->query('SELECT COUNT(id) FROM questions WHERE id_text = '.$value[0])->fetch_all();
			//print_r($row[0][0]);
			/*$qs = new Question($value[0]);
			$ct = count($qs->questions);
			unset($qs);*/
			$ct = $row[0][0]; 			// эта хрень количество вопросов 
			if ($ct == $right_count) {
				$this->right_texts[] = $value;
			}
		}
	}
	function get_lenght($text)
	{
		// Убираем слова короче трех символов
		$text = preg_replace("/\s.{0,3}\s/", "", $text);
           
		// Знаки припенания
		$text =  preg_replace('/[^\w\s]/u', ' ', $text);
        
		return mb_strlen( $text, 'UTF-8' );
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
		$this->questions = $db->query("SELECT * FROM questions WHERE id_text = $text_id")->fetch_all();
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
		$this->opt = $db->query("SELECT * FROM options WHERE id_question = $id_question")->fetch_all();
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
	public $u;		// user @array
	public $uid;
	public $reg_time;
	public $old; 	// readed text
	function __construct($uid)
	{
		if ($this->u = $this->getUser($uid)) {
			$this->id = $this->u[0];
			$this->uid = $this->u[1];
			$this->reg_time = $this->u[2];
			$this->old = $this->getOld($this->id);	// получаем прочитанное ранее
		}
	}
	private function getOld($id)
	{
		$result = DB::get_instance()->query("SELECT * FROM old WHERE id = $id")->fetch_all();
		if (count($result)) {
			return $result[0];
		}
		return FALSE;
	}
	private function getUser($uid)
	{
		$result = DB::get_instance()->query("SELECT * FROM users WHERE uid = $uid")->fetch_all();
		if (count($result)) {
			return $result[0];
		}
		return FALSE;
	}
	private function setUser($uid)
	{
		$result = DB::get_instance()->query("INSERT INTO City ('uid','time') VALUES (".$uid.", ".time().")");

	}
	// Проверка, есть ли такой юзер
		# Проверка на основе имени юзера и id
	// Если есть, берем данные из базы
	// Если такого нет, добавляем его в базу

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
