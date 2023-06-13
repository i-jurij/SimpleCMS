<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\MyClasses\Upload\UploadFile;
use Illuminate\Http\Request;

class ServiceEditController extends Controller
{
    use \App\Traits\DeleteFile;
    public array $data;

    public function go(Request $request)
    {
        // if (!empty($_POST['page_for_edit']) && !empty($_POST['action'])) {
        if (!empty($request->input('page_for_edit')) && !empty($request->input('action'))) {
            $ar = explode('#', test_input($request->input('page_for_edit')));
            $this->data['page_id'] = $ar[0];
            $this->data['page_title'] = $ar[1];
            $this->data['action'] = test_input($_POST['action']);
            if ($this->data['action'] === 'cats_add') {
                $this->data['name'] = 'Добавить категории';
            } elseif ($this->data['action'] === 'serv_add') {
                $this->data['name'] = 'Добавить услуги';
            } elseif ($this->data['action'] === 'cats_del') {
                $this->data['name'] = 'Удалить категории';
            } elseif ($this->data['action'] === 'serv_del') {
                $this->data['name'] = 'Удалить услуги';
            }

            $this->data['page_cats'] = ServiceCategory::where('page_id', $this->data['page_id'])
                ->select('id', 'page_id', 'name', 'image')
                ->get();

            $this->data['page_cats_serv'] = Service::where('page_id', $this->data['page_id'])
                ->whereNotNull('category_id')
                ->orWhere('category_id', '<>', '')
                ->select('id', 'page_id', 'category_id', 'name')
                ->get();

            $this->data['page_serv'] = Service::where('page_id', $this->data['page_id'])
            ->whereNull('category_id')
            ->orWhere('category_id', '=', '')
            ->select('id', 'page_id', 'name', 'image')
            ->get();
        } elseif (!empty($request->input('cats_name'))) { // CAT ADD
            $this->data['name'] = 'Добавить категории';

            if (!empty($request->input('page_id'))) {
                $ar = explode('#', test_input($request->input('page_id')));
                $page_id = $ar[0];
                $page_title = $ar[1];
                $this->data['res'] = 'Страница "'.$page_title.'".<br />';
                $post = array_map('test_input', $request->input('cats_name'));
                // PROCESSING $_FILES
                $load = new UploadFile();
                if ($load->issetData()) {
                    foreach ($load->files as $input => $input_array) {
                        foreach ($input_array as $key => $file) {
                            // SET the vars for class
                            if ($input === 'cats_img') {
                                $load->defaultVars();
                                $load->create_dir = true; // let create dest dir if not exists
                                $load->dest_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.$page_id);
                                // $load->tmp_dir = public_path('tmp');
                                $load->file_size = 3 * 1024 * 1024; // 3MB
                                $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                $load->new_file_name = $post[$key];
                                $load->replace_old_file = true;
                            }
                            // PROCESSING DATA
                            if ($load->execute($input_array, $key, $file)) {
                                if (!empty($load->message)) {
                                    $this->data['res'] .= $load->message;
                                }
                                // sql insert
                                $cat_name = $post[$key];
                                $cat_img = 'categories'.DIRECTORY_SEPARATOR.$page_id.DIRECTORY_SEPARATOR.$load->new_file_name;
                                $iscat = ServiceCategory::where('page_id', $page_id)
                                    ->where(function ($query) use ($cat_name, $cat_img) {
                                        $query->where('name', $cat_name)
                                            ->orWhere('image', $cat_img);
                                    })
                                    ->first();

                                if ($iscat) {
                                    $this->data['res'] .= 'Категория с таким именем "'.$cat_name.'" уже существует в базе.<br />';
                                } else {
                                    $insert = [
                                        'page_id' => $page_id,
                                        'image' => $cat_img,
                                        'name' => $cat_name,
                                        'description' => '',
                                    ];

                                    if (ServiceCategory::insert($insert)) {
                                        $this->data['res'] .= 'Данные категории "'.$cat_name.'" внесены в базу.<br />';
                                    } else {
                                        $this->data['res'] .= 'Ошибка! Данные категории "'.$cat_name.'" НЕ внесены в базу.<br />';
                                    }
                                }
                            } else {
                                if (!empty($load->error)) {
                                    $this->data['res'] .= $load->error;
                                }
                                continue;
                            }
                            // CLEAR TMP FOLDER
                            if (!$load->delFilesInDir($load->tmp_dir)) {
                                if (!empty($load->error)) {
                                    $this->data['res'] .= $load->error;
                                }
                            }
                        }
                    }
                } else {
                    $this->data['res'] .= 'Фото для загрузки не были выбраны.<br />';
                }
            } else {
                $this->data['res'] = 'Не выбрана страница для редактирования.';
            }
        } elseif (!empty($request->serv_name)) { // SERV ADD
            $this->data['name'] = 'Добавить услуги';
            if (!empty($request->page_id)) {
                $ar = explode('#', $request->page_id);
                $page_id = $ar[0];
                $page_title = $ar[1];
                $this->data['res'] = 'Страница "'.$page_title.'",<br />';

                if (!empty($request->cat_id)) {
                    $cat_ar = explode('#', $request->cat_id);
                    $cat_id = $cat_ar[0];
                    $cat_title = $cat_ar[1];
                    $this->data['res'] .= 'Категория "'.$cat_title.'".<br />';
                }
                // POST processing
                $serv_name = $request->serv_name;
                $serv_desc = $request->serv_desc;
                $price = $request->price;
                $duration = $request->duration;
                // services for category
                if (!empty($cat_id)) {
                    foreach ($serv_name as $k => $serv) {
                        $re = "/^-?(?:\d+|\d*\.\d+|\d*\,\d+)$/";
                        if (preg_match($re, $price[$k])) {
                            $price_end = $price[$k];
                        } else {
                            $price_end = '';
                        }

                        $isserv = Service::where(['name' => $serv, 'category_id' => $cat_id, 'page_id' => $page_id])->first();

                        if ((bool) $isserv) {
                            $this->data['res'] .= 'Услуга с таким именем "'.$serv.'" уже существует в базе.<br />';
                        } else {
                            $insert = [
                                'page_id' => $page_id,
                                'category_id' => $cat_id,
                                'name' => $serv,
                                'image' => '',
                                'price' => $price_end,
                                'duration' => $duration[$k],
                                'description' => '',
                            ];

                            try {
                                Service::insert($insert);
                                $this->data['res'] .= 'Данные услуги "'.$serv.'" внесены в базу.<br />';
                            } catch (\Throwable $th) {
                                $this->data['res'] .= 'Ошибка! Данные услуги "'.$serv.'" НЕ внесены в базу.<br />'.$th.'<br>';
                            }
                        }
                    }
                } else { // service for page
                    // PROCESSING $_FILES
                    $load = new UploadFile();
                    if ($load->issetData()) {
                        foreach ($load->files as $input => $input_array) {
                            foreach ($input_array as $key => $file) {
                                // SET the vars for class
                                if ($input === 'serv_img') {
                                    $load->defaultVars();
                                    $load->create_dir = true; // let create dest dir if not exists
                                    $load->dest_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.$page_id);
                                    // $load->tmp_dir = public_path('tmp');
                                    $load->file_size = 3 * 1024 * 1024; // 3MB
                                    $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                                    $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                                    $load->new_file_name = $serv_name[$key];
                                    $load->replace_old_file = true;
                                }
                                // PROCESSING DATA
                                if ($load->execute($input_array, $key, $file)) {
                                    if (!empty($load->message)) {
                                        $this->data['res'] .= $load->message;
                                    }

                                    // sql data insert
                                    $re = "/^-?(?:\d+|\d*\.\d+|\d*\,\d+)$/";
                                    if (preg_match($re, $price[$key])) {
                                        $price_end = $price[$key];
                                    } else {
                                        $price_end = '';
                                    }

                                    $isserv = Service::where('name', $serv_name[$key])
                                        ->where('page_id', $page_id)
                                        ->first();

                                    if ((bool) $isserv) {
                                        $this->data['res'] .= 'Услуга с таким именем "'.$serv_name[$key].'" уже существует в базе.<br />';
                                    } else {
                                        $insert = [
                                            'page_id' => $page_id,
                                            'name' => $serv_name[$key],
                                            'image' => 'services'.DIRECTORY_SEPARATOR.$page_id.DIRECTORY_SEPARATOR.$load->new_file_name,
                                            'description' => $serv_desc[$key],
                                            'price' => $price_end,
                                            'duration' => $duration[$key],
                                        ];

                                        try {
                                            Service::insert($insert);
                                            $this->data['res'] .= 'Данные услуги "'.$serv_name[$key].'" внесены в базу.<br />';
                                        } catch (\Throwable $th) {
                                            $this->data['res'] .= 'Ошибка! Данные услуги "'.$serv_name[$key].'" НЕ внесены в базу.<br />'.$th.'<br>';
                                        }
                                    }
                                } else {
                                    if (!empty($load->error)) {
                                        $this->data['res'] .= $load->error;
                                    }
                                    continue;
                                }
                                // CLEAR TMP FOLDER
                                if (!$load->delFilesInDir($load->tmp_dir)) {
                                    if (!empty($load->error)) {
                                        $this->data['res'] .= $load->error;
                                    }
                                }
                            }
                        }
                    } else {
                        $this->data['res'] .= 'Фото для загрузки не были выбраны.<br />';
                    }
                }
            } else {
                $this->data['res'] = 'Не выбрана страница для редактирования.';
            }
        } elseif (!empty($request->cat_del)) { // CAT DEL
            $this->data['name'] = 'Удалить категории';
            $this->data['res'] = '';
            foreach ($request->cat_del as $value) {
                if (!empty($value)) {
                    $ar = explode('#', $value);
                    $id_ar[] = $ar[0];
                    // del img
                    if (mb_strstr(self::deleteFile(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$ar[1])), 'was removed')) {
                        $this->data['res'] .= 'Изображение "'.$ar[1].'" было удалено.<br />';
                    } else {
                        $this->data['res'] .= self::deleteFile(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$ar[1])).'<br />';
                    }

                    // sql del services of category
                    $sql_serv = Service::where('category_id', $ar[0])->select('id')->get();
                    $sql_serv->each(function ($serv) {
                        $serv->masters()->detach();
                        $serv->destroy($serv->id);
                    });

                    $this->data['res'] .= 'Данные услуг категории "'.$ar[2].'" удалены из базы.<br />';

                    // sql del category
                    $sql = ServiceCategory::destroy($ar[0]);
                    if ($sql > 0) {
                        $this->data['res'] .= 'Данные категории "'.$ar[2].'" удалены из базы.<br />';
                    } else {
                        $this->data['res'] .= 'Данные категории "'.$ar[2].'" НЕ удалены или не существуют в базе.<br />';
                    }
                } else {
                    $this->data['res'] .= 'Пустые входные данные.<br />';
                }
            }

            // del page dir into imgs/categories dir if it empty
            if (del_empty_dir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.$request->page_id))) {
                $this->data['res'] .= 'Пустой каталог "storage/app/public/images/categories/'.$request->page_id.'" удален.<br />';
            }
        } elseif (!empty($request->serv_del)) { // SERV DEL
            $this->data['name'] = 'Удаление услуг';
            $this->data['res'] = '';
            foreach ($request->serv_del as $serv) {
                $arr = explode('#', $serv);
                $serv_id = $arr[0];
                $serv_name = $arr[1];
                $page_id = $arr[2];
                if (is_numeric($arr[3])) {
                    $cat_id = $arr[3];
                } elseif (is_string($arr[3])) {
                    $serv_img = $arr[3];
                }

                // del serv img
                if (isset($serv_img)) {
                    if (mb_strstr(self::deleteFile(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv_img)), 'was removed')) {
                        $this->data['res'] .= 'Изображение "'.$serv_img.'" было удалено.<br />';
                    } else {
                        $this->data['res'] .= self::deleteFile(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$serv_img)).'<br />';
                    }
                }

                // del page dir into imgs/services dir if it empty
                if (del_empty_dir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.$page_id))) {
                    $this->data['res'] .= 'Пустой каталог "storage/app/public/images/services/'.$page_id.'" удален.<br />';
                }
                // sql del services
                $sql = Service::where('id', $serv_id)->delete();
                if ($sql > 0) {
                    $this->data['res'] .= 'Данные услуги "'.$serv_name.'" удалены из базы.<br />';
                } else {
                    $this->data['res'] .= 'Данные услуги "'.$serv_name.'" НЕ удалены или не существуют в базе.<br />';
                }
            }
        } else {
            $this->data['name'] = 'Нет данных';
            $this->data['res'] = 'Пустые входные данные.';
        }

        return $this->data;
    }
}
