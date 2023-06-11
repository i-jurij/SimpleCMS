/admin/page/create - creating client page:

alias - single lowcase word only letters

single_page = 'yes' - if you only need to get data from table Pages from DB and load data into page_template.blade.php
single_page = 'no' - if you need to have controller and model and migration and view for page (and menu path from db tables)

service_page = 'yes' - if you need to create page with services CRUD (resource controler)
As models for service page - model Pages, model Categories, model Services
As view - resources/other/service_page.blade.php

After creating service page you can add categories and services to page.
Also admin panel has items for creating and editing categories and services.

After creating the page - open files in your editor or IDE and edit.
Also put route in routes/web.php.

table master_service:
master_id
service_id
