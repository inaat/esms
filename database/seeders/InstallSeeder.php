<?php


namespace Database\Seeders;

use App\Models\SystemSetting;

use Illuminate\Database\Seeder;
class InstallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ "id"=>"2", "org_name"=>"Explainer School", "org_short_name"=>"EXS", "org_address"=>"Swat", "org_contact_number"=>"+923428927305", "org_email"=>"inayatullahkks@gmail.com", "org_website"=>"", "org_logo"=>"", "tag_line"=>"To Make Sense", "page_header_logo"=>"", "id_card_logo"=>"", "org_favicon"=>"", "currency_id"=>"91", "currency_symbol_placement"=>"before", "start_date"=>"2021-10-14", "date_format"=>"d-m-Y", "time_format"=>"12", "time_zone"=>"Asia/Karachi", "start_month"=>"", "transaction_edit_days"=>"30", "email_settings"=>'{"mail_driver":"smtp","mail_host":null,"mail_port":null,"mail_username":null,"mail_password":null,"mail_encryption":null,"mail_from_address":null,"mail_from_name":null}', "sms_settings"=>'{"sms_service":"other","nexmo_key":null,"nexmo_secret":null,"nexmo_from":null,"twilio_sid":null,"twilio_token":null,"twilio_from":null,"url":null,"send_to_param_name":"to","msg_param_name":"text","request_method":"post","header_1":null,"header_val_1":null,"header_2":null,"header_val_2":null,"header_3":null,"header_val_3":null,"param_1":null,"param_val_1":null,"param_2":null,"param_val_2":null,"param_3":null,"param_val_3":null,"param_4":null,"param_val_4":null,"param_5":null,"param_val_5":null,"param_6":null,"param_val_6":null,"param_7":null,"param_val_7":null,"param_8":null,"param_val_8":null,"param_9":null,"param_val_9":null,"param_10":null,"param_val_10":null}', "ref_no_prefixes"=>'{"student":"std1","employee":"Em","fee_payment":"FeeP","expenses_payment":"FeeP","admission":"Adm"}', "enable_tooltip"=>"1", "theme_color"=>"blue", "common_settings"=>'{"default_datatable_page_entries":"25"}']
      ];

        SystemSetting::insert($data);

        
    }
}
