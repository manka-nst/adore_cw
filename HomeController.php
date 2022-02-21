<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\About;
use App\Models\Team;
use App\Models\Slider;
use App\Models\Client;
use App\Models\Brand;
use App\Models\Portfolio;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::select('id', 'name', 'icon', 'description')->where('status', '=', 'on')->get();
        $about = About::select('id', 'title', 'description', 'image', 'year')->where('status', '=', 'on')->orderBy('year')->get();
        $team = Team::select('id', 'name', 'job', 'image', 'facebook_icon', 'twitter_icon', 'linkedIn')->where('status', '=', 'on')->get();
        $slider = Slider::select('id', 'title', 'description')->where('status', '=', 'on')->get();
        $clients = Client::select('id', 'name')->get();
        $brands = Brand::select('id', 'image')->where('status', '=', 'on')->get();
        $portfolios = Portfolio::select('id', 'name', 'description', 'image', 'client_id', 'service_id')->where('status', '=', 'on')->get();


        return view('welcome', ['services' => $services,
            'abouts' => $about,
            'teams' => $team,
            'sliders' => $slider,
            'clients' => $clients,
            'brands' => $brands,
            'portfolios' => $portfolios
        ]);

    }

    public function admin()
    {
        return view('admin')->with('statistic', [
            'events' => ['quantity' => \App\Models\Service::all()->count(), 'name' => 'Количество наших мероприятий'],
            'portfolios' => ['quantity' => \App\Models\Portfolio::all()->count(), 'name' => 'Количество прошедших ивентов'],
            'users' => ['quantity' => \App\Models\User::all()->count(), 'name' => 'Количество участников клуба'],
            'teams' => ['quantity' => \App\Models\Team::all()->count(), 'name' => 'Количество команд'],
            'brands' => ['quantity' => \App\Models\Brand::all()->count(), 'name' => 'Количество брендов-партнеров'],
            'abouts' => ['quantity' => \App\Models\About::all()->count(), 'name' => 'Количество публикаций нашей истории'],
        ]);
    }

    public function getServices()
    {
        $services = Service::select('id', 'name', 'icon', 'description')->where('status', '=', 'on')->get();

        return view('services', ['services' => $services]);
    }

    public function getPortfolio()
    {
        $portfolios = Portfolio::select('id', 'name', 'description', 'image', 'client_id', 'service_id')->where('status', '=', 'on')->get();

        return view('portfolio', ['portfolios' => $portfolios]);
    }

    public function getTeam()
    {
        $teams = Team::select('id', 'name', 'job', 'image', 'facebook_icon', 'twitter_icon', 'linkedIn')->where('status', '=', 'on')->get();

        return view('team', ['teams' => $teams]);
    }

    public function getUser()
    {
        $users = User::select('id', 'name')->get();

        return view('user', ['users' => $users]);
    }

    public function getAbout()
    {
        $abouts = About::select('id', 'title', 'description', 'image', 'year')->where('status', '=', 'on')->get();

        return view('about', ['abouts' => $abouts]);
    }

    public function getContact($id)
    {
        return view('contact')->with(['event' => Service::find($id)]);
    }

    public function storeContact(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'phone' => 'required|integer',
            'message' => 'required|string',
            'email' => 'required|string|max:255'
        ]);
        $data = $request->except(['_token']);
        $data['service_id'] = $id;
        Contact::create($data);
        return redirect()->route('welcome')->with('success', 'Message Has been submitted Successfully');
    }

    public function register()
    {
        return view('register');
    }

    public function login()
    {
        return view('login');
    }
}
