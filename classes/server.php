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
		self::$db = new mysqli("mysql-env-4315189.jelastic.regruhosting.ru", "root", "0BdPMn3QJI", "test");
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
	public $all_texts;
	public $id;
	public $name;
	public $text;
	public $lenght;

	function __construct()
	{
		$db = DB::get_instance();
		$this->all_texts = $db->query("SELECT * FROM text")->fetch_all();
		$rnd_text = $this->rnd();
		$this->id = $rnd_text[0];
		$this->text = $rnd_text[1];
		$this->name = $rnd_text[2];
		$this->lenght = $this->get_lenght($this->text);
	}
	function rnd()
	{
		$rnd_text = $this->all_texts[array_rand($this->all_texts)];
		while ( $this->check_text($rnd_text[0])  !== TRUE) {
			$rnd_text = $this->all_texts[array_rand($this->all_texts)];
		}
		return $rnd_text;
	}
	function check_text($id)
	{
		$right_count = 10;
		$qs = new Question($id);
		$ct = count($qs->questions);
		unset($qs);
		if ($right_count == $ct) {
			return TRUE;
		}
		return FALSE;
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
	public $rdt; // readed text

	// Проверка, есть ли такой юзер
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
		$url_auth = 'https://oauth.vk.com/access_token?client_id='. $this->client_id .'&client_secret='. $this->client_id .'&v=5.21&grant_type=client_credentials';
		$this->token = file_get_contents($url_auth);
	}
	function getUser()
	{
		$url = 'https://api.vk.com/method/users.get?&v=5.21&access_token='.$this->token;
	}
}