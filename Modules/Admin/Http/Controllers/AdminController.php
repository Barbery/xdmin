<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Traits\AuthenticationTrait;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Admin;
use Modules\Admin\Models\BusinessOrder;

class AdminController extends Controller
{
    use AuthenticationTrait;
    public function homePage()
    {
        return view("{$this->module}::welcome");
    }

    public function editProfile()
    {
        return view("{$this->module}::profile.form", ['Model' => Auth::user()]);
    }

    public function updateProfile()
    {
        $params = array_filter($this->request->only(['password', 'name']));
        if (!empty($params['password'])) {
            $params['password'] = bcrypt($params['password']);
        }

        if (!empty($params)) {
            Admin::where('id', $this->getId())->update($params);
        }
        return $this->response();
    }
}
