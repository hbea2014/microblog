<?php namespace Microblog\Controllers\Backend;

class Dashboard extends BaseController
{

    public function show()
    {
        $this->redirectGuestTo('login');

        $data = ['title' => 'Dashboard'];

        $this->insertUserIntoDataRenderSetContent('dashboard/layout', $data);
    }

}