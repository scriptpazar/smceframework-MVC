﻿
## Composer
https://packagist.org/packages/smce/framework

## Features

- MVC
- PSR
- Autoload
- Session Management
- Assets Manager
- Validation
- Masterpage/Layout
- Template Engine
- GRUD
- Smce Command Line
- ORM ActiveRecord(MySQL, SQLite, PostgreSQL, Oracle)
- Using ActiveRecord on Multiple Databases
- Accses Control Lists (ACL)
- Debug
- Logger
- Router
- Use SSH to Connect to a Remote Server (SSH,FTP)
- Using multiple ssh and ftp over SSH
- Exception Class
- Zip Encoding Class
- User Agent Class
- Pagination Class
- Output Class
- MemCache Class
- Redis Class
- Migration Class
- Helper Class - Enriched With Anonymous Closure Functions
- Http Exceptions Capture (404, 403 .. vs Page)
- Widgets

# Ornekler

### Smce Console help

php smce --help |
--- |


## Smce Console Grud


### New Model

php smce --grud model connectingstring table_name |
--- |


Controller
--------------------------

``` php
//controller/SiteController.php


<?php

use Smce\Core\SmController;
use Smce\Lib\SmOutput;

class SiteController extends SmController
{

    public $layout='//layouts/column1';

    public function actionIndex()
    {
        $hello="Hello World";
		
        $this->render("index",array("hello"=>$hello));
        
    }
	
	public function actionOutput()
	{
		$SmOutput=new SmOutput;
		$SmOutput->setContentType("application/json")
		//->setFileName("hello.json")
		->put(json_encode(array('message' => 'Hello, World!')));
	}

    public function actionAbout()
    {
         $this->render("pages/about");
    }
	
	
	public static function methotTest(){
		return "This method was called from SiteController";
	}

	
    public function actionLogin()
    {
		
        $model=new LoginForm();

        if (isset($_POST["LoginForm"])) {
			
            $model->attributesApply($_POST["LoginForm"]);
		
            if ($model->validate() && $model->login()) {
			
                //redirect url
                $this->redirect("panel/index");

            }
			/*
			*use Smce\Lib\SmForm;
			*print_r(SmForm::getErrorData());
			*
			*/
			
			
        }

        $this->render("login",array(
            "model"=>$model,
         ));

    }

    public function actionLogout()
    {
        Smce::app()->stateClear();
        $this->redirect("site/index");
    }

    public function error($err)
    {
         $this->render("error",array(
            "code"=>$err,
         ));
    }
}


```

View
--------------------------

``` php
//view/site/index.php
<?php echo $hello;?>
<br><br>

<b>Proje BaseUrl</b>: <?php echo Smce::app()->baseUrl?> <br />
<b>Proje BasePath</b>: <?php echo Smce::app()->basePath?><br />
<b>Layout/MasterPage</b>: <?php echo $this->layout?>
<br />
<br />
<br />
<b>Controller</b>: <?php echo BASE_CONTROLLER?><br />
<b>View</b>: <?php echo BASE_VIEW?>
<br />
<br />
<br />

<b>İp Adres</b>: <?php echo Smce::app()->ip?><br />
<br />
<br />


SiteController::methotTest(): <?=SiteController::methotTest()?>

```


Config
--------------

``` php
//config/config.php

<?php


return array(
	'appname'=>'Smce Framework',

	// autoloading model and component classes
    'import'=>array(
		'model',
		'components',
	),

	'debug'=>true,
	
	
	'urlRouter'=>array(
		'router'=>array(
			"all"=>array(),
			"router"=>array("::veriA::","::dataB::"),
		),
		'showScriptName'=>false, //false - true
		
		/*
			'showScriptName'=>true,
			
			//App Path .htaccess
			
			RewriteEngine on

			# if a directory or a file exists, use it directly
			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteCond %{REQUEST_FILENAME} !-d
			
			# otherwise forward it to index.php
			
			# RewriteRule . index.php
			RewriteRule ^(.*)$ index.php?page=$1 [L,NC]
		
		*/	
	),
	
	'components'=>array(
		
		//MySQL, SQLite, PostgreSQL, Oracle
		'ActiveRecord'=>array(
			'db1'=>array(
				"connectionString"=>"mysql://username:password@localhost/db_name"
			),
			
			/*
			'db2'=>array(
				"connectionString"=>"mysql://username:password@localhost/db_name"
			),
			*/
		),
		
		
		'SSH'=>array(
			"ssh1"=>array(
				"host"=>"ec2-xx.xx.xx.xx.compute-1.amazonaws.com",
				"username"=>"root",
				//"password"=>"",
				"port"=>"22",
				"pemfile"=>BASE_PATH."/main/data/centosKEy.pem",
			),
			
			/*
			"ssh2"=>array(
				"host"=>"ec2-xx.xx.xx.xx.compute-1.amazonaws.com",
				"username"=>"root",
				//"password"=>"",
				"port"=>"22",
				"pemfile"=>BASE_PATH."/main/data/centosKEy.pem",
			),
			*/
		),
		
		
		'MemCache'=>array(
			"mem1"=>array(
				'host' => 'localhost', 
                'port' => 11211,
			),
			
		),
		
		'Redis'=>array(
			"red1"=>array(
				'host' => 'localhost', 
                'port' => 6379,
			),
			
		),
		
		
	),
);


```

Components/Controller.php
--------------

``` php
//components/Controller.php

<?php


class Controller
{

    public static $layout='//layouts/column1';
	
	public function beforeAction()
	{
		//before
		
	}
	
	public function afterAction()
	{
		//after
	}

}


```	
	
	
Model
---------------

``` php
//model/LoginForm.php

<?php

/**
 * LoginForm class.
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
use Smce\Core\SmFormModel;

class LoginForm extends SmFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
            array('username, password', 'required'),
			// rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
			// password needs to be authenticated
            array('password', "after", 'authenticate'),//array('password', false, 'authenticate'),
			
        );
		
		/*
			
			-----RULES-----
			
				required
				valid_email
				max_len,1
				min_len,4
				exact_len,10
				alpha
				alpha_numeric
				numeric
				integer
				boolean
				float
				valid_url
				url_exists
				valid_ip
				valid_ipv4
				valid_ipv6
				valid_name
				contains,free pro basic
				
			-----RULES-----
			
		*/
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Beni Hatırla',
			'username'=>'E-mail',
			'password'=>'Parola',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	 
	public function authenticate()
	{
		
		$this->_identity=new UserIdentity($this->username,$this->password);
		if($this->_identity->authenticate() && !$this->error)
			$this->addError('password','Kullanıcı ve/veya Parola hatalı.');

	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity===null) {
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode===UserIdentity::ERROR_NONE) {
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            Smce::app()->login($this->_identity,$duration);
			return true;
		} else
			return false;
	}
}



```

Model 2
---------------------------------------

``` php
//model/UsersModel.php

<?php
//MeekroDB example

class UsersModel
{
	public function __construct()
	{
		DB::$error_handler = false;
		DB::$throw_exception_on_error = true;
	}

	public function create($array)
	{
		  //insert
          $query=DB::insert('users',$array);

	}

	public function update($array)
	{
	     //update
          $query=DB::update('users', $array, "userID=%s", $array["userID"]);

	}

	public function admin()
	{
		 //select
         $query=DB::query("SELECT * FROM users");
	     return $query;
	}

}



```


Model 3
---------------------------------------

``` php
//model/ListModel.php

<?php
//ActiveRecord example

use Smce\Core\SmActiveRecord;

class ListModel extends SmActiveRecord
{
	
	public static $table_name="list";
	
	public function rules()
	{
		return array(
			// name, password and email are required
            array('name, email', 'required'),
			
		);
	}

	public function attributeLabels()
	{
		return array(
			'name'=>'Ad Soyad',
			'email'=>'E-mail',
		);
	}
	
	
}
```

# Composer

SmTemplate // Template Engine
-----

![Alt text](http://i58.tinypic.com/33dxb80.jpg "SmTemplate")

``` php

<?php

require("vendor/autoload.php");

use Smce\Lib\SmTemplate;
use Smce\Core\SmException;

$array=array("model1"=>"Welcome to SmceFramework","model2"=>"Hello");

try{
	$SmTemplate=new SmTemplate();
	$SmTemplate->setView("index",$array);
	$SmTemplate->setLayout("column1");
	$SmTemplate->setThemeDirectory("");
	$SmTemplate->run();
}catch(SmException $e){
	echo $e->errorMessage();
}


?>

```

Layout //column1.php

``` php
<?php /* @var $this Controller */ ?>
<html>
	<head>
	</head>
<body>
	<div id="content">
	<?php echo $content; ?>
	</div><!-- content -->
</body>
</html>
```

SmGump //Validation
-----

``` php
<?php

require("vendor/autoload.php");

use Smce\Lib\SmGump;

$validator = new SmGump();


$rules = array(
	'missing'   	=> 'required',
	'email'     	=> 'valid_email',
	'max_len'   	=> 'max_len,1',
	'min_len'   	=> 'min_len,4',
	'exact_len' 	=> 'exact_len,10',
	'alpha'	       	=> 'alpha',
	'alpha_numeric' => 'alpha_numeric',
	'numeric'		=> 'numeric',
	'integer'		=> 'integer',
	'boolean'		=> 'boolean',
	'float'			=> 'float',
	'valid_url'		=> 'valid_url',
	'valid_ip'		=> 'valid_ip',
	'valid_ipv4'	=> 'valid_ipv4',
	'valid_ipv6'	=> 'valid_ipv6',
	'valid_name'    => 'valid_name',
	'contains'		=> 'contains,free pro basic'
);


$invalid_data = array(
	'missing'   	=> '',
	'email'     	=> "not a valid email\r\n",
	'max_len'   	=> "1234567890",
	'min_len'   	=> "1",
	'exact_len' 	=> "123456",
	'alpha'	       	=> "*(^*^*&",
	'alpha_numeric' => "abcdefg12345+\r\n\r\n\r\n",
	'numeric'		=> "one, two\r\n",
	'integer'		=> "1,003\r\n\r\n\r\n\r\n",
	'boolean'		=> "this is not a boolean\r\n\r\n\r\n\r\n",
	'float'			=> "not a float\r\n",
	'valid_url'		=> "\r\n\r\nhttp://add",
	'valid_ip'		=> "google.com",
	'valid_ipv4'    => "google.com",
	'valid_ipv6'    => "google.com",
	'valid_name' 	=> '*&((*S))(*09890uiadaiusyd)',
	'contains'		=> 'premium'
);

$valid_data = array(
	'missing'   	=> 'This is not missing',
	'email'     	=> 'sean@wixel.net',
	'max_len'   	=> '1',
	'min_len'   	=> '1234',
	'exact_len' 	=> '1234567890',
	'alpha'	       	=> 'ÈÉÊËÌÍÎÏÒÓÔasdasdasd',
	'alpha_numeric' => 'abcdefg12345',
	'numeric'		=> 2.00,
	'integer'		=> 3,
	'boolean'		=> FALSE,
	'float'			=> 10.10,
	'valid_url'		=> 'http://wixel.net',
	'valid_ip'		=> '69.163.138.23',
	'valid_ipv4'    => "255.255.255.255",
	'valid_ipv6'    => "2001:0db8:85a3:08d3:1319:8a2e:0370:7334",
	'valid_name' 	=> 'Sean Nieuwoudt',
	'contains'		=> 'free'
);

echo "\nBEFORE SANITIZE:\n\n";

echo "<pre>";
print_r($invalid_data);

echo "\nAFTER SANITIZE:\n\n";
print_r($validator->sanitize($invalid_data));

echo "\nTHESE ALL FAIL:\n\n";
$validator->validate($invalid_data, $rules);

// Print out the errors using the new get_readable_errors() method:
print_r($validator->get_readable_errors());

if($validator->validate($valid_data, $rules)) {
	echo "\nTHESE ALL SUCCEED:\n\n";
	print_r($valid_data);
}

echo "\nDONE\n\n";

echo "</pre>";
?>

```

SmOutput
-----

``` php
<?php

require("vendor/autoload.php");

use Smce\Lib\SmOutput;

$SmOutput=new SmOutput;

$SmOutput->setContentType("application/json")
//->setFileName("hello.json")
->put(json_encode(array('message' => 'Hello, World!')));

?>

```



## Gelistirici Hakkinda
Samed Ceylan
