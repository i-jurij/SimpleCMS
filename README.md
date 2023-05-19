/admin/page/create
single_page = 'yes' - if you only need to get data from table Pages from DB and load data into page_template.blade.php
single_page = 'no' - if you need to have controller and model and migration and view for page (and menu path from db tables)
service_page = 'yes' - if you need to create page with services CRUD (resource controler, model, migration, view etc)
