<?php

namespace Database\Seeders;

use App\Models\Master\AnswerTemplate;
use App\Models\Master\AttachmentCategory;
use App\Models\Master\BehaviorCategory;
use App\Models\Master\BehaviorCriteria;
use App\Models\Master\FeedbackBehaviorCategory;
use App\Models\Master\FeedbackWorkCategory;
use App\Models\Master\Menu;
use App\Models\Master\Module;
use App\Models\Master\Organization;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\PerspectiveIndicator;
use App\Models\Master\Question;
use App\Models\Master\Questionnaire;
use App\Models\Master\QuestionnaireSection;
use App\Models\Master\QuestionResult;
use App\Models\Master\QuestionResultHeader;
use App\Models\Master\RealizationPeriod;
use App\Models\Master\RealizationPeriodType;
use App\Models\Master\Role;
use App\Models\Master\RoleMenu;
use App\Models\Master\RoleUser;
use App\Models\Master\Unit;
use App\Models\Master\WorkPosition;
use App\Models\Master\WorkRank;
use App\Models\Master\WorkTitle;
use App\Models\Master\WorkUnit;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;

class InjectDummyData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            'title' => 'Kuisioner',
            'path' => 'questionnaire',
            'icon' => 'icon-certificate',
            'rank' => 1
        ]);
        Module::create([
            'title' => 'Hasil & Pelaporan',
            'path' => 'report',
            'icon' => 'icon-stats-bars2',
            'rank' => 2
        ]);
        Module::create([
            'title' => 'Konfigurasi',
            'path' => 'config',
            'icon' => 'icon-cog',
            'rank' => 3
        ]);
        $module = Module::where('title', 'Kuisioner')->first();
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Master',
            'title' => 'Kuisioner',
            'path' => 'questionnaire/question',
            'icon' => 'icon-file-text2',
            'rank' => 1
        ]);
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Master',
            'title' => 'Template Jawaban',
            'path' => 'questionnaire/answer-template',
            'icon' => 'icon-bookmark4',
            'rank' => 2
        ]);
        $module = Module::where('title', 'Hasil & Pelaporan')->first();
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Hasil',
            'title' => 'Hasil Kuisioner',
            'path' => 'report/questionnaire-result',
            'icon' => 'icon-pencil7',
            'rank' => 1
        ]);
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Pelaporan',
            'title' => 'Statistik',
            'path' => 'report/statistic',
            'icon' => 'icon-file-stats',
            'rank' => 1
        ]);
        $module = Module::where('title', 'Konfigurasi')->first();
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Sistem',
            'title' => 'Organisasi',
            'path' => 'config/organization',
            'icon' => 'icon-office',
            'rank' => 1
        ]);
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Sistem',
            'title' => 'Unit',
            'path' => 'config/unit',
            'icon' => 'icon-home',
            'rank' => 2
        ]);
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Sistem',
            'title' => 'Role',
            'path' => 'config/role',
            'icon' => 'icon-user-lock',
            'rank' => 3
        ]);
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Sistem',
            'title' => 'Modul & Menu',
            'path' => 'config/menu',
            'icon' => 'icon-list2',
            'rank' => 4
        ]);
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Sistem',
            'title' => 'Role Menu',
            'path' => 'config/user-role',
            'icon' => 'icon-file-locked',
            'rank' => 5
        ]);
        Menu::create([
            'module_id' => $module->id,
            'group' => 'Sistem',
            'title' => 'Pengguna',
            'path' => 'config/users',
            'icon' => 'icon-users4',
            'rank' => 6
        ]);
        $role_superadmin = Role::create([
            'name' => 'Superadmin'
        ]);
        Role::create([
            'name' => 'Admin Unit'
        ]);
        $menus = Menu::get();
        foreach ($menus as $menu) {
            RoleMenu::create([
                'role_id' => $role_superadmin->id,
                'menu_id' => $menu->id
            ]);
        }
        $organization = Organization::create([
            'name' => 'UPN Veteran Jawa Timur',
            'short_name' => 'UPNVJT',
            'address' => 'Jl. Rungkut Madya No.1, Gn. Anyar, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294'
        ]);
        Unit::create([
            'name' => 'BUK Crisis Center',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'BUK Pelaporan dan Keuangan',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'Satuan Pengawas Internal',
            'short_name' => 'SPI',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'Penerimaan Mahasiswa Baru',
            'short_name' => 'PMB',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'BUK Pengelola Lingkungan Kampus',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'UPA Bahasa',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'BUK Kepegawaian',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'BUK Sekertariat Tata Usaha',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'UPA Perpustakaan',
            'organization_id' => $organization->id
        ]);
        Unit::create([
            'name' => 'UPA Teknologi Informasi Komunikasi',
            'organization_id' => $organization->id
        ]);
        $questionnaire = Questionnaire::create([
            'title' => 'Survey Layanan',
            'description' => 'Evaluasi mutu layanan',
        ]);
        $answer_template_choices = [
            [
                'title' => 'Tidak Setuju',
                'value' => 1
            ],
            [
                'title' => 'Kurang Setuju',
                'value' => 2
            ],
            [
                'title' => 'Setuju',
                'value' => 3
            ],
            [
                'title' => 'Sangat Setuju',
                'value' => 4
            ],
        ];
        $answer_template = AnswerTemplate::create([
            'title' => 'Range Kebaikan',
            'input_type' => 'radio',
            'content' => $answer_template_choices
        ]);
        $questionnaire_section1 = QuestionnaireSection::create([
            'questionnaire_id' => $questionnaire->id,
            'title' => 'Dosen Wali'
        ]);
        $questionnaire_section2 = QuestionnaireSection::create([
            'questionnaire_id' => $questionnaire->id,
            'title' => 'UPT Telematika'
        ]);
        $q1 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section1->id,
            'code' => 'A101',
            'title' => 'Dosen wali mudah ditemui oleh mahasiswa',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q2 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section1->id,
            'code' => 'A102',
            'title' => 'Dosen wali memberikan kemudahan dalam pelayanan KHS,KRS, dll',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q3 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section1->id,
            'code' => 'A103',
            'title' => 'Dosen wali menyediakan banyak waktu yang cukup untuk konsultasi mahasiswa',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q4 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section1->id,
            'code' => 'A104',
            'title' => 'Dosen wali bersedia membantu menyelesaikan permasalahan mahasiswa',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q5 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section2->id,
            'code' => 'C101',
            'title' => 'Kondisi ruangan Puskom cukup representatif',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q6 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section2->id,
            'code' => 'C102',
            'title' => 'Petugas melayanan dengan ramah',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q7 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section2->id,
            'code' => 'C103',
            'title' => 'Jenis dan Jumlah peralatan puskom yang dibutuhkan cukup tersedia',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q8 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section2->id,
            'code' => 'C104',
            'title' => 'Kondisi peralatan cukup baik',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q9 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section2->id,
            'code' => 'C105',
            'title' => 'Akses internet cukup mudah',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $q10 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'questionnaire_section_id' => $questionnaire_section2->id,
            'code' => 'C106',
            'title' => 'Setiap permasalahan diselesaikan',
            'answer_type' => 'radio',
            'answer_content' => $answer_template_choices
        ]);
        $period_2024 = Period::create([
            'name' => 'Tahun 2024',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]);
        for ($i = 1; $i <= 100; $i++) {
            $faker = Factory::create();
            $question_result_header = QuestionResultHeader::create([
                'questionnaire_id' => $questionnaire->id,
                'period_id' => $period_2024->id,
                'name' => $faker->name(),
                'audience_type' => 'umum'
            ]);
            for ($j = 1; $j <= 10; $j++) {
                $variableName = 'q' . $j;
                QuestionResult::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question_header_id' => $question_result_header->id,
                    'question_id' => $$variableName->id,
                    'answer' => rand(3, 4)
                ]);
            }
        }
        $superadmin = User::create([
            'name' => 'Superadmin Sistem',
            'username' => '12345678910',
            'password' => bcrypt('superadmin'),
        ]);
        $personalSuperadmin = Personal::create([
            'user_id' => $superadmin->id,
            'name' => $superadmin->name,
            'gender' => 'Laki-laki',
            'address' => 'Jalan Alamat',
        ]);
        RoleUser::create([
            'role_id' => $role_superadmin->id,
            'user_id' => $superadmin->id
        ]);
        $avatar = Avatar::create($superadmin->name)->getImageObject()->encode('png');
        $filename = 'users/' . $superadmin->username . '.png';
        Storage::disk('public')->put($filename, (string)$avatar);
        $superadmin->avatar = Storage::disk('public')->url($filename);
        $superadmin->save();
    }
}
