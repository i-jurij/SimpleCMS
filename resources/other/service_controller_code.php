<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contacts;
use App\Models\Pages;

// class $classname.Controller extends Controller
class PageAliasController extends Controller
{
    protected function index($content, $page_data = '', $method_and_params = '')
    {
        // add data for head in template
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        $content['pages_menu'] = Pages::where('publish', '=', 'yes')->get()->toArray() ?? ['No pages in DB'];
        if (!empty($method_and_params) && is_array($method_and_params)) {
            if (!empty($method_and_params['method'])) {
                $method = (string) $method_and_params['method'];
            }
            if (!empty($method_and_params['params'])) {
                $params = $method_and_params['params'];
            }
        }

        // call to method of this class if exists
        if (method_exists($this::class, $method)) {
            $this->{$method}();
        } else {
            // get cat and serv data from db
            if ($this->db->db->has('serv_categories', ['page_id' => $page_id])) {
                $this->data['cat'] = $this->db->db->select('serv_categories', '*', ['page_id' => $page_id]);
            } else {
                $this->data['cat'] = [];
            }
            if ($this->db->db->has('services', ['page_id' => $page_id])) {
                $this->data['serv'] = $this->db->db->select('services', '*', ['page_id' => $page_id]);
                // min price for common categories
                $min_price = [];
                foreach ($this->data['cat'] as $cat) {
                    $sql = 'SELECT * FROM `services`
                            WHERE
                            price = ( SELECT MIN(price) FROM `services` WHERE (category_id = :cdp AND page_id = :pid))
                    ';
                    $ccf = $this->db->db->pdo->prepare($sql);
                    $ccf->bindParam(':cdp', $cat['id']);
                    $ccf->bindParam(':pid', $page_id);
                    $ccf->execute();
                    if ($rccf = $ccf->fetch(\PDO::FETCH_LAZY)) {
                        $min_price[$cat['category_name']] = $rccf->price;
                    }
                }
                asort($min_price, SORT_NATURAL);
                $this->data['min_price'] = $min_price;
            } else {
                $this->data['serv'] = [];
                $this->data['min_price'] = [];
            }
        }
    }
}
