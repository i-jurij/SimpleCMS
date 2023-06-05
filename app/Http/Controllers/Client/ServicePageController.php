<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contacts;
use App\Models\Pages;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// class $classname.Controller extends Controller
class ServicePageController extends Controller
{
    // url can be /page_alias
    // /page_alias/category/id
    // /page_alias/service/id
    // /page_alias/category/service/id
    // $class_name(page alias) = $path_array[0];
    // $path_array[1] - category or service
    // $path_array[2] - category id or service id or string "service"
    // $path_array[3] - service id
    // if count($path_array) === 1 then get all data from DB from categories and services for current page
    // if count($path_array) === 3 call to $this->show(array $data) then Service::where($path_array[3])
    // if count($path_array) === 2 all to $this->show(array $data) then check $path_array[1] and call to CategoyController or ServiceController with id = $path_array[2]

    public function index($content, $page_data = '', $path_array = [])
    {
        $this_show_method_data = [];
        // add data for head in template
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        $content['pages_menu'] = Pages::where('publish', '=', 'yes')->get()->toArray() ?? ['No pages in DB'];

        if (Schema::hasTable('service_categories') && Schema::hasTable('services')) {
            if (count($path_array) === 1) {
                // get cat and serv data from db
                if (ServiceCategory::where('page_id', $page_data[0]['id'])->first()) {
                    $data['cat'] = ServiceCategory::where('page_id', $page_data[0]['id'])->get()->toArray();
                } else {
                    $data['cat'] = [];
                }
                if (Service::where('page_id', $page_data[0]['id'])->first()) {
                    $data['serv'] = Service::where('page_id', $page_data[0]['id'])->get()->toArray();
                    // min price for common categories
                    $min_price = [];

                    if (!empty($data['cat'])) {
                        $pdo = DB::connection()->getPdo();

                        foreach ($data['cat'] as $cat) {
                            $sql = 'SELECT * FROM `services`
                                    WHERE
                                    price = ( SELECT MIN(price) FROM `services` WHERE (category_id = :cdp AND page_id = :pid))
                            ';
                            $ccf = $pdo->prepare($sql);
                            $ccf->bindParam(':cdp', $cat['id']);
                            $ccf->bindParam(':pid', $page_data[0]['id']);
                            $ccf->execute();
                            if ($rccf = $ccf->fetch(\PDO::FETCH_LAZY)) {
                                $min_price[$cat['category_name']] = $rccf->price;
                            }
                        }
                        asort($min_price, SORT_NATURAL);
                        $data['min_price'] = $min_price;
                    } else {
                        $data['min_price'] = [];
                    }
                } else {
                    $data['serv'] = [];

                    $data['min_price'] = [];
                }
            } elseif (count($path_array) === 2 || count($path_array) === 3) {
                $this->show($path_array);
            } else {
                $data['res'] = 'Too many parameters in the query string.';
                // abort(404);
            }
        } else {
            $data = [];
        }

        return view('client_manikur.client_pages.service_page', ['content' => $content, 'page_data' => $page_data, 'data' => $data, 'this_show_method_data' => $this_show_method_data]);
    }

    public function show(array $path_array)
    {
        $this_show_method_data = [];
        if (count($path_array) === 2) {
            // check $path_array[1] and call to CategoryController or ServiceController with id = $path_array[2]
            if ($path_array[1] === 'category' || $path_array[1] === 'service') {
                // check if $path_array[2] is valid id
                if (preg_match('/\A\d{1,5}\z/', $path_array[2])) {
                    $id = $path_array[2];
                } else {
                    $this_show_method_data = ['The data not found.'];
                }

                if (!empty($id) && $path_array[1] === 'category') {
                    $this_show_method_data['cat'] = ServiceCategory::first($id)->toArray();
                    $this_show_method_data['serv'] = Service::where('category_id', $id)->get()->toArray();
                }
                if (!empty($id) && $path_array[1] === 'service') {
                    $this_show_method_data['serv'] = Service::first($id)->toArray();
                }
            } else {
                $this_show_method_data = ['The data not found.'];
            }
        } elseif (count($path_array) === 3) {
            if ($path_array[1] === 'category' && $path_array[2] === 'service') {
                if (preg_match('/\A\d{1,5}\z/', $path_array[3])) {
                    $id = $path_array[3];
                    $this_show_method_data['serv'] = Service::first($id)->with('category')->toArray();
                } else {
                    $this_show_method_data = ['The data not found.'];
                }
            } else {
                $this_show_method_data = ['The data not found.'];
            }
        }

        return $this_show_method_data;
    }
}
