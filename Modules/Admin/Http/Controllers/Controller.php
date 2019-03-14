<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use View;

class Controller extends BaseController
{
    protected $view            = '';
    protected $indexView       = '';
    protected $createView      = '';
    protected $editView        = '';
    protected $showView        = '';
    protected $module          = 'admin';
    protected $timestampFields = [];

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $routeInfo = explode('.', pageRouteName());
        array_pop($routeInfo);
        $route = implode('.', $routeInfo);
        View::share('route', $route);
        View::share('appConfig', config("{$this->module}.appConfig"));
        View::share('module', $this->module);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $indexView = empty($this->indexView) ? "{$this->view}.index" : $this->indexView;
        return view("{$this->module}::{$indexView}");
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $createView = empty($this->createView) ? "{$this->view}.form" : $this->createView;
        return view("{$this->module}::{$createView}");
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $showView = empty($this->showView) ? "{$this->view}.show" : $this->showView;
        $Model    = $this->getModelQuery()->find($id);
        return view("{$this->module}::{$showView}", ['Model' => $Model]);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {

        $editView = empty($this->editView) ? "{$this->view}.form" : $this->editView;
        $Model    = $this->getModelQuery()->find($id);
        return view("{$this->module}::{$editView}", ['Model' => $Model]);
    }

    public function search()
    {
        return parent::index();
    }
}
