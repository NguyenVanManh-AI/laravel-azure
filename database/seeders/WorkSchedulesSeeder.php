<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\HospitalDepartment;
use App\Models\Province;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Repositories\HospitalServiceRepository;
use Illuminate\Database\Seeder;

class WorkSchedulesSeeder extends Seeder
{
    // chia nhỏ lịch làm việc ra
    public function divideTime($timeArray, $maxMinutes)
    {
        $start = strtotime($timeArray[0]);
        $end = strtotime($timeArray[1]);
        $dividedTimes = [];
        while ($start < $end) {
            $nextEnd = $start + ($maxMinutes * 60);
            if ($nextEnd > $end) {
                break;
            }
            $dividedTimes[] = [date('H:i', $start), date('H:i', $nextEnd)];
            $start = $nextEnd;
        }

        return $dividedTimes;
    }

    public function renderDate()
    {
        return date('Y-m-d', mt_rand(strtotime('2000-01-01'), strtotime('2002-12-29')));
    }

    public function renderPhone()
    {
        return '09' . rand(10000000, 99999999);
    }

    // address_patient
    public $address_patients;

    public $patients;

    public $health_conditions;

    public function __construct()
    {
        $this->address_patients = Province::all();
        foreach ($this->address_patients as $address) {
            $address->name = $address->name . ' - Việt Nam';
        }

        $this->patients = [
            ['name' => 'Nguyễn Văn An', 'email' => 'nguyenvanan@yopmail.com', 'gender' => 1],
            ['name' => 'Trần Thị Bình', 'email' => 'tranthibinh@yopmail.com', 'gender' => 0],
            ['name' => 'Lê Thanh Châu', 'email' => 'lethanhchau@yopmail.com', 'gender' => 0],
            ['name' => 'Phạm Đức Duy', 'email' => 'phamducduy@yopmail.com', 'gender' => 1],
            ['name' => 'Võ Thị Giang', 'email' => 'vothigiang@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Thị Hà', 'email' => 'nguyenthaha@yopmail.com', 'gender' => 0],
            ['name' => 'Hoàng Minh Hoàng', 'email' => 'hoangminhhoang@yopmail.com', 'gender' => 1],
            ['name' => 'Lê Thị Hương', 'email' => 'lethihuong@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Văn Hùng', 'email' => 'nguyenvanhung@yopmail.com', 'gender' => 1],
            ['name' => 'Nguyễn Khánh', 'email' => 'nguyenkhanh@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Thị Lan', 'email' => 'nguyenthilan@yopmail.com', 'gender' => 0],
            ['name' => 'Phạm Thị Linh', 'email' => 'phamthilinh@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Văn Minh', 'email' => 'nguyenvanminh@yopmail.com', 'gender' => 1],
            ['name' => 'Trần Văn Nam', 'email' => 'tranvannam@yopmail.com', 'gender' => 1],
            ['name' => 'Nguyễn Thị Ngọc', 'email' => 'nguyenthingoc@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Văn Nguyên', 'email' => 'nguyenvannguyen@yopmail.com', 'gender' => 0],
            ['name' => 'Trần Văn Phong', 'email' => 'tranvanphong@yopmail.com', 'gender' => 1],
            ['name' => 'Vũ Thị Phương', 'email' => 'vuthiphuong@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Văn Quân', 'email' => 'nguyenvanquan@yopmail.com', 'gender' => 1],
            ['name' => 'Lê Văn Quang', 'email' => 'levanquang@yopmail.com', 'gender' => 1],
            ['name' => 'Nguyễn Văn Quốc', 'email' => 'nguyenvanquoc@yopmail.com', 'gender' => 1],
            ['name' => 'Phạm Thị Quyên', 'email' => 'phamthiquyen@yopmail.com', 'gender' => 0],
            ['name' => 'Lê Văn Sơn', 'email' => 'levanson@yopmail.com', 'gender' => 1],
            ['name' => 'Phạm Văn Thành', 'email' => 'phamvanthanh@yopmail.com', 'gender' => 1],
            ['name' => 'Lê Thị Thảo', 'email' => 'lethithao@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Thị Thu', 'email' => 'nguyenthithu@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Thị Trâm', 'email' => 'nguyenthitram@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Thị Trang', 'email' => 'nguyenthitrang@yopmail.com', 'gender' => 0],
            ['name' => 'Võ Thị Vân', 'email' => 'vothivan@yopmail.com', 'gender' => 0],
            ['name' => 'Nguyễn Văn Vinh', 'email' => 'nguyenvanvinh@yopmail.com', 'gender' => 1],
        ];

        $this->health_conditions = [
            'Cảm thấy nhức đầu, chán ăn, tay chân mệt mỏi.',
            'Cảm thấy khó ngủ, chán ăn, suy nghĩ nhiều và mệt mỏi.',
            'Cảm giác mệt mỏi, suy nghĩ nhiều, đau đầu và khó chịu.',
            'Cảm giác khó ngủ, suy nghĩ nhiều, mệt mỏi và buồn bã.',
            'Cảm thấy sốt, đau họng, và mệt mỏi.',
            'Cảm giác khó chịu ở vùng bụng, buồn nôn và mệt mỏi.',
            'Cảm thấy đau rát, sưng, và đỏ ở mắt.',
            'Cảm giác căng thẳng, buồn bã, và mệt mỏi suốt ngày.',
            'Cảm thấy khó chịu ở cổ, vai, và đau lưng.',
            'Cảm giác mệt mỏi, căng thẳng, và khó chịu toàn thân.',
            'Cảm thấy đau nhức ở khắp cơ thể, không muốn ăn và mệt mỏi.',
            'Cảm giác buồn bã, mất hứng thú, và khó tập trung.',
            'Cảm thấy khó thở, đau ngực và suy nghĩ nhiều.',
            'Cảm giác mệt mỏi, buồn chán, và không muốn làm gì cả.',
            'Cảm thấy đau ở khắp cơ thể, khó chịu và không muốn nói chuyện.',
        ];
    }

    public function renderAddress()
    {
        return $this->address_patients->shuffle()->first()->name;
    }

    public function renderPatient()
    {
        return $this->patients[array_rand($this->patients)];
    }

    public function renderHealth()
    {
        return $this->health_conditions[array_rand($this->health_conditions)];
    }

    public function bookAvise($doctors, $hospital, $times, $users)
    {
        $randomKey = array_rand($times); // random 1 key
        $time = $times[$randomKey]; // giá trị time ứng với key
        unset($times[$randomKey]); // xóa time đó khỏi mảng

        $user = null;
        if ($users) {
            $user = $users->shuffle()->first(); // xáo trộn
        }
        $render_patient = (object) $this->renderPatient();

        $doctor = $doctors->shuffle()->first(); // xáo trộn
        $department = Department::find($doctor->id_department);
        $hospital_department = HospitalDepartment::where('id_department', $department->id)
            ->where('id_hospital', $hospital->id)->first();

        $patient = (object) [
            'date_of_birth_patient' => $this->renderDate(),
            'name_patient' => $render_patient->name,
            'gender_patient' => $render_patient->gender,
            'email_patient' => $render_patient->email,
            'phone_patient' => $this->renderPhone(),
            'address_patient' => $this->renderAddress(),
            'health_condition' => $this->renderHealth(),
        ];
        $infors = (object) [
            'messsge' => false,
            'date_of_birth_patient' => $patient->date_of_birth_patient,
            'name_patient' => $patient->name_patient,
            'email_patient' => $patient->email_patient,
            'gender_patient' => $patient->gender_patient,
            'phone_patient' => $patient->phone_patient,
            'address_patient' => $patient->address_patient,
            'health_condition' => $patient->health_condition,
            'name_doctor' => $doctor->name,
            'email_doctor' => $doctor->email,
            'phone_doctor' => $doctor->phone,
            'name_department' => $department->name,
            'name_hospital' => $hospital->name,
            'phone_hospital' => $hospital->phone,
            'address_hospital' => $hospital->address,
            'price' => $hospital_department->price,
            'time' => $time,
        ];
        $infors->content = view('emails.book_advise', compact(['infors', 'user']))->render();
        $data = [
            'id_doctor' => $doctor->id_doctor,
            'id_user' => $user->id_user ?? null,
            'id_service' => null,
            'price' => $infors->price,
            'time' => json_encode($infors->time),
            'content' => $infors->content,
            'name_patient' => $infors->name_patient,
            'date_of_birth_patient' => $infors->date_of_birth_patient,
            'gender_patient' => $infors->gender_patient,
            'email_patient' => $infors->email_patient,
            'phone_patient' => $infors->phone_patient,
            'address_patient' => $infors->address_patient,
            'health_condition' => $infors->health_condition,
            'is_confirm' => random_int(0, 1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        WorkSchedule::create($data);

        return $times;
    }

    public function bookService($hospital, $services, $times, $specify, $users)
    {
        $randomKey = array_rand($times); // random 1 key
        $time = $times[$randomKey]; // giá trị time ứng với key
        unset($times[$randomKey]); // xóa time đó khỏi mảng

        // user
        $user = null;
        if ($users) {
            $user = $users->shuffle()->first(); // xáo trộn
        }
        $render_patient = (object) $this->renderPatient();

        // service and doctor
        $service = $services->shuffle()->first();

        $doctor = null;
        if ($specify) {
            $doctors = User::join('infor_doctors', 'infor_doctors.id_doctor', '=', 'users.id')
                ->where('role', 'doctor')->where('id_hospital', $service->id_hospital)
                ->where('id_department', $service->id_department)
                ->get();
            $doctor = $doctors->shuffle()->first(); // xáo trộn
        }

        $department = Department::find($service->id_department);
        $hospital_department = HospitalDepartment::where('id_department', $department->id)
            ->where('id_hospital', $hospital->id)->first();

        $patient = (object) [
            'date_of_birth_patient' => $this->renderDate(),
            'name_patient' => $render_patient->name,
            'gender_patient' => $render_patient->gender,
            'email_patient' => $render_patient->email,
            'phone_patient' => $this->renderPhone(),
            'address_patient' => $this->renderAddress(),
            'health_condition' => $this->renderHealth(),
        ];
        $infors = (object) [
            'messsge' => false,
            'date_of_birth_patient' => $patient->date_of_birth_patient,
            'name_patient' => $patient->name_patient,
            'email_patient' => $patient->email_patient,
            'gender_patient' => $patient->gender_patient,
            'phone_patient' => $patient->phone_patient,
            'address_patient' => $patient->address_patient,
            'health_condition' => $patient->health_condition,
            'name_service' => $service->name,
            'price' => $service->price,
            'name_department' => $department->name,
            'name_hospital' => $hospital->name,
            'phone_hospital' => $hospital->phone,
            'address_hospital' => $hospital->address,
            'time' => $time,
        ];

        $infors->content = view('emails.book_service', compact(['infors', 'user']))->render();
        $data = [
            'id_doctor' => $doctor->id_doctor ?? null,
            'id_user' => $user->id_user ?? null,
            'id_service' => $service->id_hospital_service,
            'price' => $infors->price,
            'time' => json_encode($infors->time),
            'content' => $infors->content,
            'name_patient' => $infors->name_patient,
            'date_of_birth_patient' => $infors->date_of_birth_patient,
            'gender_patient' => $infors->gender_patient,
            'email_patient' => $infors->email_patient,
            'phone_patient' => $infors->phone_patient,
            'address_patient' => $infors->address_patient,
            'health_condition' => $infors->health_condition,
            'is_confirm' => random_int(0, 1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        WorkSchedule::create($data);

        return $times;
    }

    public function run()
    {
        // intervals
        $intervals = [];
        $intervals = array_merge($intervals, $this->divideTime(['07:30', '11:30'], 30));
        $intervals = array_merge($intervals, $this->divideTime(['13:30', '17:30'], 30));
        $intervals = array_merge($intervals, $this->divideTime(['18:00', '20:00'], 30));

        // 7 ngày trong tuần
        $datesOfWeek = [];
        $currentDate = now()->startOfWeek();
        for ($i = 1; $i <= 7; $i++) {
            $datesOfWeek[] = $currentDate->format('Y-m-d');
            $currentDate->addDay(1);
        }

        // times
        $rootTimes = [];
        foreach ($datesOfWeek as $index => $date) {
            foreach ($intervals as $index => $interval) {
                $time = (object) [
                    'date' => $date,
                    'interval' => $interval,
                ];
                $rootTimes[] = $time;
            }
        }

        $users = User::join('infor_users', 'infor_users.id_user', '=', 'users.id')
            ->where('role', 'user')->get();

        $hospitals = User::join('infor_hospitals', 'infor_hospitals.id_hospital', '=', 'users.id')
            ->where('role', 'hospital')->limit(3)->get();

        foreach ($hospitals as $index => $hospital) {
            $doctors = User::join('infor_doctors', 'infor_doctors.id_doctor', '=', 'users.id')
                ->where('role', 'doctor')->where('id_hospital', $hospital->id_hospital)
                ->get();
            // hiện tại tất cả service và advise đều có time_advise là 30 phút
            // ADVISE
            // book 5 advise có đăng nhập
            $times = $rootTimes;
            for ($i = 0; $i < 5; $i++) {
                $times = $this->bookAvise($doctors, $hospital, $times, $users);
            }
            for ($i = 0; $i < 5; $i++) {
                $times = $this->bookAvise($doctors, $hospital, $times, null);
            }

            // SERVICE
            $filter = (object) [
                'id_hospital' => $hospital->id_hospital,
                'is_delete' => 0,
            ];
            $services = HospitalServiceRepository::searchAll($filter)->get();

            // book service có đăng nhập , có chỉ định bác sĩ
            for ($i = 0; $i < 5; $i++) {
                $times = $this->bookService($hospital, $services, $times, $specify = true, $users);
            }

            // book service có đăng nhập , không chỉ định bác sĩ
            for ($i = 0; $i < 5; $i++) {
                $times = $this->bookService($hospital, $services, $times, $specify = false, $users);
            }

            // book service ẩn danh , có chỉ định bác sĩ
            for ($i = 0; $i < 5; $i++) {
                $times = $this->bookService($hospital, $services, $times, $specify = true, null);
            }

            // book service ẩn dnah , không chỉ định bác sĩ
            for ($i = 0; $i < 5; $i++) {
                $times = $this->bookService($hospital, $services, $times, $specify = false, null);
            }
        }
    }
}
