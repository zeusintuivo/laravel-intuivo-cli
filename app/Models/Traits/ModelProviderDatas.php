<?php namespace PHIWeb\Models\Traits;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Collection;
use Carbon;
use Faker;
use DB;

use PHIWeb\Models\Param\Location;
use PHIWeb\Models\Param\Param;
use PHIWeb\Models\Person\Person;
use PHIWeb\Models\DailyLog\DailyLog;
use PHIWeb\Models\Organization\School;
use PHIWeb\Models\Organization\Insurance;
use PHIWeb\Models\Immunization\VaccineLog;

/**
 * Trait created for PHIWeb
 * use PHIWeb\Models\Trait\ModelProviderDatas;
 * @author Jesus Alcaraz <jesus@gammapartners.com>, miguel <miguel@gammapartners.com>
 * @version 1
 */
trait ModelProviderDatas {

    #
    #  Declare Locations Random
    #
    private $countries          ;    
    private $states             ;       
    private $counties           ;     
    private $cities             ;       
    private $providences        ;  
    private $jurisdictions      ;
    private $districts          ;    
   
    #  
    # people  
    #  
    private $student_ids        ;
    private $staff_ids          ;

    private $people_id          ;
    private $people_ids         ;
    private $person_last_id     ;
    private $person_first_id    ;    
  
    private $open_by_id         ;
    private $open_at            ;
    private $closed_by_id       ;
    private $closed_at          ;
    private $printed_by_id      ;
    private $printed_at         ;
  
    #older than 39   
    private $user_ids           ;
    private $older_ids          ;
  
    private $principal_id       ;
    private $principal_ids      ;
  
    private $provider_id        ;
    private $provider_last_id   ;
    private $provider_first_id  ;
      
    private $given_by_id        ;
    private $given_by           ;
    private $acronym            ;
    private $given_at           ;
      
    #younger than 18  
    private $patient_id         ;
    private $patient_ids        ;
    private $patient_last_id    ;
    private $patient_first_id   ;


    #
    # Schools
    #
    private $school_id          ;
    private $school_ids         ;

    #
    # DailyLogs
    #
    private $daily_log_id       ;
    private $daily_log_ids      ;
    private $daily_log_last_id  ;
    private $daily_log_first_id ;

    #
    # MedLogs
    #
    private $med_log_id       ;
    private $med_log_ids      ;
    private $med_log_last_id  ;
    private $med_log_first_id ;

    #
    # Insurance
    #
    private $insurance_id       ;
    private $insurance_ids      ;
    private $insurance_last_id  ;
    private $insurance_first_id ;

    #
    # Vaccines
    #
    private $vaccine_id       ;
    private $vaccine_ids      ;
    private $vaccine_last_id  ;
    private $vaccine_first_id ;

    # 
    # other
    #
    private $password           ;
    private $currenttime        ;
    private $faker              ;





    public function __construct() {
        $this->password = bcrypt('123456');
        $this->currenttime = Carbon\Carbon::now();
        $this->faker = Faker\Factory::create();
    }
    private function requireLocations() {
        //
        //  Locations Load Arrays
        //
        $this->countries=     (Location::arrayByTypeLongerName('country_id'));
        $this->states=        (Location::arrayByTypeLongerName('state_id'));
        $this->counties=      (Location::arrayByTypeLongerName('county_id'));
        $this->cities=        (Location::arrayByTypeLongerName('city_id'));
        $this->providences=   (Location::arrayByTypeLongerName('providence_id'));
        $this->jurisdictions= (Location::arrayByTypeLongerName('jurisdiction_id'));
        $this->districts=     (Location::arrayByTypeLongerName('district_id'));


    }

    private function requireParams() {
        //
        // Params Arrays
        //
        $this->alert_type_ids               =(Param::arrayByTypeLongerName('alert_type_id'));
        $this->category_ids                 =(Param::arrayByTypeLongerName('category_id'));
        $this->daily_ids                    =(Param::arrayByTypeLongerName('daily_id'));
        $this->daily_log_comment_type_ids    =(Param::arrayByTypeLongerName('daily_log_comment_type_id'));
        $this->dailylog_comment_desc_ids    =(Param::arrayByTypeLongerName('daily_log_comment_type_id', 'type', 'longer_name'));
        $this->dose_ids                     =(Param::arrayByTypeLongerName('dose_id'));
        $this->dose_type_ids                =(Param::arrayByTypeLongerName('dose_type_id'));
        $this->end_schedules_ids            =(Param::arrayByTypeLongerName('end_schedules_id'));
        $this->frequency_ids                =(Param::arrayByTypeLongerName('frequency_id'));
        $this->grade_ids                    =(Param::arrayByTypeLongerName('grade_id'));
        $this->guardian_type_ids            =(Param::arrayByTypeLongerName('guardian_type_id'));
        $this->homeroom_ids                 =(Param::arrayByTypeLongerName('homeroom_id'));
        $this->insurance_type_ids           =(Param::arrayByTypeLongerName('insurance_type_id'));
        $this->legend_ids                   =(Param::arrayByTypeLongerName('legend_id'));
        $this->daily_log_location_ids       =(Param::arrayByTypeLongerName('daily_log_location_id'));
        $this->log_comment_type_ids         =(Param::arrayByTypeLongerName('log_comment_type_id'));
        $this->logger_type_ids              =(Param::arrayByTypeLongerName('logger_type_id'));
        $this->medication_category_ids      =(Param::arrayByTypeLongerName('medication_category_id'));
        $this->medication_name_ids          =(Param::arrayByTypeLongerName('medication_name_id'));
        $this->medication_type_ids          =(Param::arrayByTypeLongerName('medication_type_id'));
        $this->month_ids                    =(Param::arrayByTypeLongerName('month_id'));
        $this->nature_of_injury_ids         =(Param::arrayByTypeLongerName('nature_of_injury_id'));
        $this->new_category_ids             =(Param::arrayByTypeLongerName('new_category_id'));
        $this->notification_type_ids        =(Param::arrayByTypeLongerName('notification_type_id'));
        $this->order_status_ids             =(Param::arrayByTypeLongerName('order_status_id'));
        $this->organization_type_ids        =(Param::arrayByTypeLongerName('organization_type_id'));
        $this->param_type_ids               =(Param::arrayByTypeLongerName('param_type_id'));
        $this->permision_type_ids           =(Param::arrayByTypeLongerName('permision_type_id'));
        $this->phone_type_ids               =(Param::arrayByTypeLongerName('phone_type_id'));
        $this->procedure_ids                =(Param::arrayByTypeLongerName('procedure_id'));
        $this->procedure_type_ids           =(Param::arrayByTypeLongerName('procedure_type_id'));
        $this->referred_to_ids              =(Param::arrayByTypeLongerName('referred_to_id'));
        $this->relationship_type_ids        =(Param::arrayByTypeLongerName('relationship_type_id'));
        $this->repeat_every_ids             =(Param::arrayByTypeLongerName('repeat_every_id'));
        $this->report_type_ids              =(Param::arrayByTypeLongerName('report_type_id'));
        $this->role_type_ids                =(Param::arrayByTypeLongerName('role_type_id'));
        $this->route_ids                    =(Param::arrayByTypeLongerName('route_id'));
        $this->schedule_cycle_by_ids        =(Param::arrayByTypeLongerName('schedule_cycle_by_id'));
        $this->schedule_cycle_ids           =(Param::arrayByTypeLongerName('schedule_cycle_id'));
        $this->school_district_ids          =(Param::arrayByTypeLongerName('school_district_id'));
        $this->school_type_ids              =(Param::arrayByTypeLongerName('school_type_id'));
        $this->screen_type_ids              =(Param::arrayByTypeLongerName('screen_type_id'));
        $this->signed_order_type_ids        =(Param::arrayByTypeLongerName('signed_order_type_id'));
        $this->staff_type_ids               =(Param::arrayByTypeLongerName('staff_type_id'));
        $this->status_ids                   =(Param::arrayByTypeLongerName('status_id'));
        $this->student_type_ids             =(Param::arrayByTypeLongerName('student_type_id'));
        $this->subroute_ids                 =(Param::arrayByTypeLongerName('subroute_id'));
        $this->time_period_ids              =(Param::arrayByTypeLongerName('time_period_id'));
        $this->user_type_ids                =(Param::arrayByTypeLongerName('user_type_id'));
        $this->vaccine_log_comment_type_ids =(Param::arrayByTypeLongerName('vaccine_log_comment_type_id'));
        $this->vaccine_type_ids             =(Param::arrayByTypeLongerName('vaccine_type_id'));
        $this->weekday_ids                  =(Param::arrayByTypeLongerName('weekday_id')); 

    }

    private function requirePeople() {
        //
        // Person Load Arrays
        //
        $this->people_ids        = Person::lists('id');
        $this->person_last_id    = last(Person::lists('id'));
        $this->person_first_id   = Person::first()->id;

        //For nurse, doctor, developer, admin, user, 
        $this->user_ids = [1000,1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011];

        //older than 39 
        $this->older_ids         = Person::where('dob','<', Carbon\Carbon::createFromDate(1975, 5, 21))->where('id','>',999)->lists('id');
        $this->provider_last_id  = Person::where('dob','<', Carbon\Carbon::createFromDate(1975, 5, 21))->where('id','>',999)->orderBy('id','desc')->take(1)->lists('id')[0];
        $this->provider_first_id = Person::where('dob','<', Carbon\Carbon::createFromDate(1975, 5, 21))->where('id','>',999)->orderBy('id','asc')->take(1)->lists('id')[0];
        
        $this->given_by_ids      =  $this->older_ids;
        $this->principal_ids     =  $this->older_ids;
        
        //younger than 18 
        $this->student_ids       = (new Collection(  DB::select('call  getStudents();') ))->lists('first_name','id');
        $this->staff_ids         = (new Collection(  DB::select('call  getStaff();') ))->lists('first_name','id');
        $this->patient_ids       = Person::where('dob','>', Carbon\Carbon::createFromDate(1996, 5, 21))->where('id','>',999)->lists('id');
        $this->patient_last_id   = Person::where('dob','>', Carbon\Carbon::createFromDate(1996, 5, 21))->where('id','>',999)->orderBy('id','desc')->take(1)->lists('id')[0];
        $this->patient_first_id  = Person::where('dob','>', Carbon\Carbon::createFromDate(1996, 5, 21))->where('id','>',999)->orderBy('id','asc')->take(1)->lists('id')[0];


    }

    private function requireSchools() {
        $this->school_ids        = School::lists('id');   
    }

    private function requireDailyLogs() {
        //
        // DailyLog Load Arrays
        //
        $this->daily_log_ids      = DailyLog::lists('id');
        $this->daily_log_last_id  = last(DailyLog::lists('id'));
        $this->daily_log_first_id = DailyLog::first()->id; 
    }
    private function requireMedLogs() {
        //
        // DailyLog Load Arrays
        //
        $this->med_log_ids      = MedLog::lists('id');
        $this->med_log_last_id  = last(MedLog::lists('id'));
        $this->med_log_first_id = MedLog::first()->id; 
    }
    private function requireInsurances() {
        //
        // DailyLog Load Arrays
        //
        $this->insurance_ids      = Insurance::lists('id');
        $this->insurance_last_id  = last(Insurance::lists('id'));
        $this->insurance_first_id = Insurance::first()->id; 
    }
    private function requireVaccines() {
        //
        // DailyLog Load Arrays
        //
        $this->vaccine_ids      = VaccineLog::lists('id');
        $this->vaccine_last_id  = last(VaccineLog::lists('id'));
        $this->vaccine_first_id = VaccineLog::first()->id; 
    }

    private function pickLocations() {
        //
        //  Locations Random Pick One
        //
        $this->country_id         = array_rand( $this->countries );
        $this->state_id           = array_rand( $this->states );
        $this->county_id          = array_rand( $this->counties );
        $this->city_id            = array_rand( $this->cities );
        //TODO for now these are null 
        // $this->providence_id   = array_rand( $this->providences);
        // $this->jurisdiction_id = array_rand( $this->jurisdictions);
        $this->district_id        = array_rand( $this->districts);
    }

    private function pickParams() {
        //
        //  Params Random Pick One
        //
        $this->alert_type_id                  = array_rand($this->alert_type_ids);
        $this->category_id                    = array_rand($this->category_ids);
        $this->daily_id                       = array_rand($this->daily_ids);
        $this->daily_log_comment_type_id       = array_rand($this->daily_log_comment_type_ids);
        $this->dose_id                        = array_rand($this->dose_ids);
        $this->dose_type_id                   = array_rand($this->dose_type_ids);
        $this->end_schedules_id               = array_rand($this->end_schedules_ids);
        $this->frequency_id                   = array_rand($this->frequency_ids);
        $this->grade_id                       = array_rand($this->grade_ids);
        $this->guardian_type_id               = array_rand($this->guardian_type_ids);
        $this->homeroom_id                    = array_rand($this->homeroom_ids);
        $this->insurance_type_id              = array_rand($this->insurance_type_ids);
        $this->legend_id                      = array_rand($this->legend_ids);
        $this->daily_log_location_id          = array_rand($this->daily_log_location_ids);
        $this->log_comment_type_id            = array_rand($this->log_comment_type_ids);
        $this->logger_type_id                 = array_rand($this->logger_type_ids);
        $this->medication_category_id         = array_rand($this->medication_category_ids);
        $this->medication_name_id             = array_rand($this->medication_name_ids);
        $this->medication_type_id             = array_rand($this->medication_type_ids);
        $this->month_id                       = array_rand($this->month_ids);
        $this->nature_of_injury_id            = array_rand($this->nature_of_injury_ids);
        $this->new_category_id                = array_rand($this->new_category_ids);
        $this->notification_type_id           = array_rand($this->notification_type_ids);
        $this->order_status_id                = array_rand($this->order_status_ids);
        $this->organization_type_id           = array_rand($this->organization_type_ids);
        $this->param_type_id                  = array_rand($this->param_type_ids);
        $this->permision_type_id              = array_rand($this->permision_type_ids);
        $this->phone_type_id                  = array_rand($this->phone_type_ids);
        $this->procedure_id                   = array_rand($this->procedure_ids);
        $this->procedure_type_id              = array_rand($this->procedure_type_ids);
        $this->referred_to_id                 = array_rand($this->referred_to_ids);
        $this->relationship_type_id           = array_rand($this->relationship_type_ids);
        $this->repeat_every_id                = array_rand($this->repeat_every_ids);
        $this->report_type_id                 = array_rand($this->report_type_ids);
        $this->role_type_id                   = array_rand($this->role_type_ids);
        $this->route_id                       = array_rand($this->route_ids);
        $this->schedule_cycle_by_id           = array_rand($this->schedule_cycle_by_ids);
        $this->schedule_cycle_id              = array_rand($this->schedule_cycle_ids);
        $this->school_district_id             = array_rand($this->school_district_ids);
        $this->school_type_id                 = array_rand($this->school_type_ids);
        $this->screen_type_id                 = array_rand($this->screen_type_ids);
        $this->signed_order_type_id           = array_rand($this->signed_order_type_ids);
        $this->staff_type_id                  = array_rand($this->staff_type_ids);
        $this->status_id                      = array_rand($this->status_ids);
        $this->student_type_id                = array_rand($this->student_type_ids);
        $this->subroute_id                    = array_rand($this->subroute_ids);
        $this->time_period_id                 = array_rand($this->time_period_ids);
        $this->user_type_id                   = array_rand($this->user_type_ids);
        $this->vaccine_log_comment_type_id    = array_rand($this->vaccine_log_comment_type_ids);
        $this->vaccine_type_id                = array_rand($this->vaccine_type_ids);
        $this->weekday_id                     = array_rand($this->weekday_ids);
 
    } 
    private function pickPeople() {
        //
        //  People Random Pick One
        //

        //Get a random number for a  person             
        $this->patient_id    = $this->faker->randomElement($this->patient_ids);

        $this->open_by_id    = $this->faker->randomElement($this->user_ids);
        $this->closed_by_id  = $this->faker->randomElement($this->user_ids);
        $this->printed_by_id = $this->faker->randomElement($this->user_ids);
        
        $this->open_at       = $this->faker->dateTimeBetween($startDate = '-1 years',  $endDate = 'now');
        $this->closed_at     = $this->faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now');
        $this->printed_at    = $this->faker->dateTimeBetween($startDate = '-6 months', $endDate = 'now');

        $this->provider_id   = $this->faker->randomElement($this->user_ids);
        $this->principal_id  = $this->faker->randomElement($this->older_ids);
        
        $this->given_by_id   = $this->faker->randomElement($this->user_ids);
        $this->given_by      = Person::find($this->given_by_id);
        $this->given_at      = $this->faker->dateTimeBetween($startDate = '-6 months', $endDate = 'now');
        $this->words         = explode(" ", $this->given_by->first_name." ".$this->given_by->last_name);
        $this->acronym       = "";
        //acronym

        foreach ($this->words as $w) {
          $this->acronym .= $w[0];
        }

    }
    private function pickSchools() {
        $this->school_id     =  $this->faker->randomElement($this->school_ids);   
    }    
    private function pickDailyLogs() {
        $this->daily_log_id     =  $this->faker->randomElement($this->daily_log_ids);  

    }  
    private function pickMedLogs() {
        $this->med_log_id     =  $this->faker->randomElement($this->med_log_ids);  

    }   
    private function pickInsurances() {
        $this->insurance_id     =  $this->faker->randomElement($this->insurance_ids);  

    }   
    private function pickVaccines() {
        $this->vaccine_id     =  $this->faker->randomElement($this->vaccine_ids);  

    }
} //end trait