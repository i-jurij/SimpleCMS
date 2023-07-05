### PAGES edit
/admin/page/create - creating client page:

alias - single lowcase word only letters

single_page = 'yes' - if you only need to get data from table Pages from DB and load data into default view (page_template.blade.php).
single_page = 'no' - if you need to have controller and model and migration and view for page (or alias or title for menu path from db tables)

service_page = 'yes' - if you need to create page with services CRUD (resource controler)
As models for service page - model Pages, model Categories, model Services
As view - resources/other/service_page.blade.php

After creating service page you can add categories and services to page.
Also admin panel has items for creating and editing categories and services.

After creating the page - open files in your editor or IDE and edit.
Also put route in routes/web.php.

### SERVICE PAGE edit
In form service_page = yes.
An entry will be created in the database.

### SERVICE edit
First create the services, then the masters.

### MASTERS edit
All masters must have a some service,
but if you don't need this - the ALL masters should NOT have any services.
Otherwise, in the logic of order processing errors may occur.
Logic:
if each master have at least one service - check appointments times for current master;
if masters not services - we believe that every master performs any service
and check appointments times for all masters
and if at least one master is free - client can sign up.

When masters created also user created with status "user"
with "name" - number of phone (+01112223344)
with email - number of phone@com.com (+01112223344@com.com)
with password - "password"


### SIGN UP
### Settings:
### By moder of organisation:
`endtime = "17:00"`
Время, после которого даты начинаются с завтрашней (те запись на сегодня уже недоступна)
The time after which the dates start from tomorrow (those records are no longer available for today)

`lehgth_cal = 14`
Количество отображаемых дней для записи
The number of days displayed for an appointment

`tz = "Europe/Simferopol"`
Часовой пояс
Timezone

`period = 60`
Интервал времен для записи (09:00, 10:00, 11:00, ...),
мб любой, преобразуется кратно 10,, то есть 7 мин -> 10 мин, 23 мин -> 30 мин и тп
кроме промежутков > 10, но < 15 - преобразуется в 15 минутный промежуток
Time interval for an appointment, can be any, converted multiple of 10
but if 10 < $period < 15 then = 15

`lunch = ["12:00", 60]`
Массив c двумя значениями: время начала HH:mm и длительность обеденного перерыва в минутах
An array with two values: the start time HH:mm and the duration of the lunch break in minutes

`worktime = ['09:00', '19:00']`
Рабочее время $worktime[0] - начало, $worktime[1] - конец
Working time $worktime[0] - start, $worktime[1] - end

`org_weekend = {'Сб': '14:00', 'Вс': ''}`
Постоянно планируемые в организации выходные, ключ - название дня,
значение - пустое, если целый день выходной,
или время начала отдыха в 24часовом формате HH:mm
Weekends that are constantly planned in the organization, the key is the name of the day,
the value is empty if the whole day is off,
or the start time of the rest in the 24-hour format HH:mm

`holiday = ['2023-02-23', '2023-03-08', '2023-03-17']` - праздничные дни хоть на 10 лет вперед

### DATA related to a specific MASTER:
`rest_day_time = {'2023-03-15': [], '2023-03-13': ['16:00', '17:00', '18:00'],'2023-03-14': ['10:00', '11:00', '14:00'] }`
Запланированные выходные дни и часы мастера,
получены из рабочего графика мастера, если массив значений пуст - выходной целый день.
Значение равно началу времени отгула, длительность не указывается и будет равна $period,
те, если период = 60 минут, а отсутствовать мастер будет 2 часа после 17:00
запись такая `rest_day_time = {'дата YYYY-mm-dd': ['17:00', '18:00']}`

The scheduled days off and the master's hours,
are obtained from the master's work schedule, if the array of values is empty - the whole day off.
The value is equal to the beginning of the time off, the duration is not specified and will be equal to $period,
those if the period = 60 minutes, and the master will be absent 2 hours after 17:00
the entry is `rest_day_time = {'date YYYY-mm-dd' => ['17:00', '18:00']}`

`exist_app_date_time_arr`
Объект предыдущих записей к мастеру
в формате `{'date': {time: 'duration', ...}, ...)`,
где date - datetime (часы и минуты 00:00)
а time - datetime (с ненулевым временем): 'duration' - длительность услуги в минутах,
длительность можно не указывать (null or ''), тогда она считается равной $period

Array of previous entries to the master
in the array format `{'date': {time: 'duration', ...}, ...)`
