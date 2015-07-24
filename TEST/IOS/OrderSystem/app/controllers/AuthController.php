<?php

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function showSignUp()
    {
        return View::make('auth/signup');
    }

    public function execSignUp()
    {
        $validation_rule = array(
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4'
        );

        $validator = Validator::make(Input::all(), $validation_rule);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        try {
            Sentry::register(array(
                'email' => Input::get('email'),
                'password' => Input::get('password'),
            ), true);
            return Redirect::route('employee.index');
        } catch (Exception $e) {
            $this->messageBag->add('all', Lang::get('auth/message.signup.error'));
        }
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }
    public function showLogin()
    {
        return View::make('auth/login');
    }
    public function execLogin()
    {
        $validation_rule = array(
            'email' => 'required|email',
            'password' => 'required');
        $validator = Validator::make(Input::all(), $validation_rule);
        if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        try{
            $user = Sentry::authenticate(Input::only('email', 'password'), true);
            Sentry::login($user, true);
            return Redirect::route('employees.index');
        }
        catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            $this->messageBag->add('all', Lang::get('auth/message.account_suspended'));
        }
        catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
            $this->messageBag->add('all', Lang::get('auth/message.account_banned'));
        }
        catch (Exception $e) {
            $this->messageBag->add('all', Lang::get('auth/message.login.error'));
        }
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }
    public function execLogout()
    {
        Sentry::logout();
        return View::make('auth.login');
    }
}