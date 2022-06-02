switch($_GET['action'])
{ 
	case "about" :
		require_once("about.php"); // страница "О Нас"
		break;
	case "contacts" :
		require_once("contacts.php"); // страница "Контакты"
		break;
	case "feedback" :
		require_once("feedback.php"); // страница "Обратная связь"
		break;
	default : 
		require_once("page404.php"); // страница "404"
	break;
}

ini_set('display_errors', 1);
require_once 'application/bootstrap.php';

require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';
Route::start();

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule .* index.php [L]

class Route
{
	static function start()
	{
		
		$controller_name = 'Main';
		$action_name = 'index';
		
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		
		if ( !empty($routes[1]) )
		{	
			$controller_name = $routes[1];
		}
		
		
		if ( !empty($routes[2]) )
		{
			$action_name = $routes[2];
		}

		
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		

		$model_file = strtolower($model_name).'.php';
		$model_path = "application/models/".$model_file;
		if(file_exists($model_path))
		{
			include "application/models/".$model_file;
		}

		
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "application/controllers/".$controller_file;
		if(file_exists($controller_path))
		{
			include "application/controllers/".$controller_file;
		}
		else
		{
			
			Route::ErrorPage404();
		}
		

		$controller = new $controller_name;
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{
			
			$controller->$action();
		}
		else
		{
			
			Route::ErrorPage404();
		}
	
	}
	
	function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
    }
}

class Model
{
	public function get_data()
	{
	}
}

class View
{
	public $template_view; 
	
	function generate($content_view, $template_view, $data = null)
	{
		
		if(is_array($data)) {
			
			extract($data);
		}
		
		include 'application/views/'.$template_view;
	}
}

class Controller {
	
	public $model;
	public $view;
	
	function __construct()
	{
		$this->view = new View();
	}
	
	function action_index()
	{
	}
}

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Главная</title>
</head>
<body>
	<?php include 'application/views/'.$content_view; ?>
</body>
</html>
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<script src="/js/jquery-1.6.2.js" type="text/javascript"></script>
class Controller_Main extends Controller
{
	function action_index()
	{	
		$this->view->generate('main_view.php', 'template_view.php');
	}
}
<h1>Добро пожаловать!</h1>
<p>
<img src="/images/office-small.jpg" align="left" >
<a href="/"> TEAM</a> - команда первоклассных специалистов в области разработки веб-сайтов с многолетним опытом коллекционирования мексиканских масок, бронзовых и каменных статуй из Индии и Цейлона, барельефов и изваяний, созданных мастерами Экваториальной Африки пять-шесть веков назад...
class Model_Portfolio extends Model
{
	public function get_data()
	{	
		return array(
			
			array(
				'Year' => '2012',
				'Site' => 'http://DunkelBeer.ru',
				'Description' => 'Промо-сайт темного пива Dunkel от немецкого производителя Löwenbraü выпускаемого пивоваренной компанией "CАН ИнБев".'
			),
			array(
				'Year' => '2012',
				'Site' => 'http://ZopoMobile.ru',
				'Description' => 'Русскоязычный каталог китайских телефонов компании Zopo на базе Android OS и аксессуаров к ним.'
			),
			// todo
		);
	}
}
class Controller_Portfolio extends Controller
{

	function __construct()
	{
		$this->model = new Model_Portfolio();
		$this->view = new View();
	}
	
	function action_index()
	{
		$data = $this->model->get_data();		
		$this->view->generate('portfolio_view.php', 'template_view.php', $data);
	}
}
<h1>Портфолио</h1>
<p>
<table>

<tr><td>Год</td><td>Проект</td><td>Описание</td></tr>
<?php

	foreach($data as $row)
	{
		echo '<tr><td>'.$row['Year'].'</td><td>'.$row['Site'].'</td><td>'.$row['Description'].'</td></tr>';
	}
	
?>
</table>
</p>

</p>
