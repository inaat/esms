<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Holiday;
use App\Models\SessionYear;
use App\Models\Slider;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ApiController extends Controller
{

    public function logout(Request $request) {
        try {
            // $user = $request->user();
            // $user->fcm_id = '';
            // $user->save();
            // $user->currentAccessToken()->delete();
            $accessToken = Auth::user()->token();

    	DB::table('oauth_refresh_tokens')
    		->where('access_token_id', $accessToken->id)
    		->update(['revoked' => true]);

    	$accessToken->revoke();
            $response = array(
                'error' => false,
                'message' => 'Logout Successfully done.',
                'code' => 200,
            );
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
    }

    public function getHolidays(Request $request) {
        // $validator = Validator::make($request->all(), [
        //     'assignment_id' => 'nullable|numeric',
        //     'subject_id' => 'nullable|numeric',
        // ]);

        // if ($validator->fails()) {
        //     $response = array(
        //         'error' => true,
        //         'message' => $validator->errors()->first(),
        //     );
        //     return response()->json($response);
        // }
        // $student = $request->user()->student;

        try {
           // $data = Holiday::get();
       
            $data = 
            array (
              0 => 
              array (
                'id' => 1,
                'date' => '2023-05-13',
                'title' => 'New Year',
                'description' => 'New year in india',
                'created_at' => '2023-06-13T11:39:09.000000Z',
                'updated_at' => '2023-06-13T11:39:09.000000Z',
              ),
              1 => 
              array (
                'id' => 3,
                'date' => '2023-08-15',
                'title' => 'Independence day',
                'description' => 'India got Independence on this day',
                'created_at' => '2023-06-13T11:39:54.000000Z',
                'updated_at' => '2023-06-13T11:39:54.000000Z',
              ),
              2 => 
              array (
                'id' => 4,
                'date' => '2023-09-20',
                'title' => 'Black friday',
                'description' => NULL,
                'created_at' => '2023-06-13T11:40:10.000000Z',
                'updated_at' => '2023-06-13T11:40:10.000000Z',
              ),
              3 => 
              array (
                'id' => 5,
                'date' => '2023-07-01',
                'title' => 'Rath Yatra',
                'description' => 'Rath Yatra is an optional holiday.',
                'created_at' => '2023-06-14T04:23:26.000000Z',
                'updated_at' => '2023-06-14T04:23:26.000000Z',
              ),
              4 => 
              array (
                'id' => 6,
                'date' => '2023-07-13',
                'title' => 'Guru Purnima',
                'description' => 'Guru Purnima (Asadha Purnima, Vyasa Purnima) is a festival where many Hindus and Buddhists pay respect to their Guru or spiritual guide.',
                'created_at' => '2023-06-14T04:24:42.000000Z',
                'updated_at' => '2023-06-14T04:24:42.000000Z',
              ),
              5 => 
              array (
                'id' => 7,
                'date' => '2023-07-14',
                'title' => 'Green',
                'description' => NULL,
                'created_at' => '2023-06-15T17:13:13.000000Z',
                'updated_at' => '2023-06-15T17:13:13.000000Z',
              ),
              6 => 
              array (
                'id' => 8,
                'date' => '2023-07-01',
                'title' => 'Ashadhi Beej',
                'description' => NULL,
                'created_at' => '2023-06-16T11:28:25.000000Z',
                'updated_at' => '2023-06-16T11:28:25.000000Z',
              ),
              7 => 
              array (
                'id' => 9,
                'date' => '2023-06-21',
                'title' => 'Yoga Day',
                'description' => NULL,
                'created_at' => '2023-06-16T11:29:18.000000Z',
                'updated_at' => '2023-06-16T11:29:18.000000Z',
              ),
              8 => 
              array (
                'id' => 10,
                'date' => '2023-09-05',
                'title' => 'Teachers Day',
                'description' => NULL,
                'created_at' => '2023-06-16T11:29:56.000000Z',
                'updated_at' => '2023-06-16T11:29:56.000000Z',
              ),
              9 => 
              array (
                'id' => 11,
                'date' => '2023-01-14',
                'title' => 'Makarsakranti',
                'description' => NULL,
                'created_at' => '2023-06-16T11:30:47.000000Z',
                'updated_at' => '2023-06-16T11:30:47.000000Z',
              ),
              10 => 
              array (
                'id' => 12,
                'date' => '2023-07-31',
                'title' => 'end of month',
                'description' => NULL,
                'created_at' => '2023-06-16T11:33:46.000000Z',
                'updated_at' => '2023-06-16T11:33:46.000000Z',
              ),
              11 => 
              array (
                'id' => 13,
                'date' => '2023-07-31',
                'title' => 'Hariyali Teej',
                'description' => NULL,
                'created_at' => '2023-06-16T11:36:34.000000Z',
                'updated_at' => '2023-06-16T11:36:34.000000Z',
              ),
              12 => 
              array (
                'id' => 14,
                'date' => '2023-07-24',
                'title' => 'Bonalu',
                'description' => 'This festival is a Telangana traditional Hindu festival centered on the Goddess Mahakali.',
                'created_at' => '2023-06-16T11:38:31.000000Z',
                'updated_at' => '2023-06-16T11:38:31.000000Z',
              ),
              13 => 
              array (
                'id' => 15,
                'date' => '2023-08-09',
                'title' => 'Muharram',
                'description' => 'Muharram is the first month of the Islamic calendar.',
                'created_at' => '2023-06-16T11:40:01.000000Z',
                'updated_at' => '2023-06-16T11:40:01.000000Z',
              ),
              14 => 
              array (
                'id' => 16,
                'date' => '2023-08-12',
                'title' => 'Raksha Bandhan',
                'description' => 'Raksha Bandhan is a popular festival in the Hindu tradition.  On this day, sisters tie a rakhi, around the wrists of their brothers. On this day, sisters tie a rakhi, around the wrists of their brothers.',
                'created_at' => '2023-06-16T11:41:15.000000Z',
                'updated_at' => '2023-06-16T11:41:15.000000Z',
              ),
              15 => 
              array (
                'id' => 17,
                'date' => '2023-08-16',
                'title' => 'Parsi New Year',
                'description' => NULL,
                'created_at' => '2023-06-16T11:41:56.000000Z',
                'updated_at' => '2023-06-16T11:41:56.000000Z',
              ),
              16 => 
              array (
                'id' => 18,
                'date' => '2023-08-28',
                'title' => 'Parkash Utsav Sri Guru Granth Sahib Ji',
                'description' => NULL,
                'created_at' => '2023-06-16T11:42:36.000000Z',
                'updated_at' => '2023-06-16T11:42:36.000000Z',
              ),
              17 => 
              array (
                'id' => 19,
                'date' => '2023-08-31',
                'title' => 'Ganeshchaturthi',
                'description' => NULL,
                'created_at' => '2023-06-16T11:42:56.000000Z',
                'updated_at' => '2023-06-16T11:42:56.000000Z',
              ),
              18 => 
              array (
                'id' => 20,
                'date' => '2023-08-18',
                'title' => 'Janmastami',
                'description' => NULL,
                'created_at' => '2023-06-16T11:43:34.000000Z',
                'updated_at' => '2023-06-16T11:43:34.000000Z',
              ),
            );
           
          
          
            $response = array(
                'error' => false,
                'message' => "Holidays Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function getSliders(Request $request) {
        try {
           $data = Slider::get();
        
// $data = [
  
//         [
//             "id" => 1,
//             "image" =>
//                 "https://e-school.wrteam.in/storage/sliders/99Ed5v87WrdS6doPVYFEcT1eSpOaztHJdaBYqasV.png"
//         ],
//         [
//             "id" => 2,
//             "image" =>
//                 "https://e-school.wrteam.in/storage/sliders/WOlf6BFSEzUt0TyOJ4565l9gTK8b5gkYU8e7lkrX.png"
//         ]
  
// ];

            $response = array(
                'error' => false,
                'message' => "Sliders Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function getSessionYear(Request $request) {
        try {
           // $session_year = getSettings('session_year');
            //$session_year_id = $session_year['session_year'];

            //$data = SessionYear::find($session_year_id);
            $data=[
                "id"=> 1,
                "name"=> "2022-23",
                "default"=> 1,
                "start_date"=> "2022-06-01",
                "end_date"=> "2023-04-30",
                "created_at"=> "2022-06-13T11:05:05.000000Z",
                "updated_at"=> "2022-12-06T08:31:48.000000Z",
                "deleted_at"=> null
        ];
            $response = array(
                'error' => false,
                'message' => "Session Year Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function getSettings(Request $request) {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:privacy_policy,contact_us,about_us,terms_condition,app_settings',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            //$settings = getSettings();
            if ($request->type == "app_settings") {
               // $session_year = $settings['session_year'] ?? "";
                //$calender = !empty($session_year) ? SessionYear::find($session_year) : null;
                 $calender=
                 array (
                   'id' => 1,
                   'name' => '2022-23',
                   'default' => 1,
                   'start_date' => '2022-06-01',
                   'end_date' => '2023-04-30',
                   'created_at' => '2022-06-13T11:05:05.000000Z',
                   'updated_at' => '2022-12-06T08:31:48.000000Z',
                   'deleted_at' => NULL,
                 );
                 $data['app_link'] = 'https://play.google.com/store/apps/details?id=com.antalyaswat.com.pk';
                 $data['ios_app_link'] = 'https://play.google.com/store/apps/details';
                 $data['app_version'] = '1.0.1+2';
                 $data['ios_app_version'] = '1.0.0+2';
                 $data['force_app_update'] = '0';
                 $data['app_maintenance'] = '0';
             
                $data['session_year'] = $calender;
                $data['school_name'] ='antalya swat school Management System';
                $data['school_tagline'] ='Learn, grow and succeed with iTECH';
                $data['teacher_app_link'] ='https://play.google.com/store/apps/details?id=com.wrteam.eschool.teacher';
                $data['teacher_ios_app_link'] ='https://apps.apple.com/us/app/eschool-teacher/id1638324167';
                $data['teacher_app_version'] ='1.0.4';
                $data['teacher_ios_app_version'] ='1.0.3';
                $data['teacher_force_app_update'] ='0';
                $data['teacher_app_maintenance'] ='0';
               
                // $data['app_link'] = $settings['app_link'] ?? "";
                // $data['ios_app_link'] = $settings['ios_app_link'] ?? "";
                // $data['app_version'] = $settings['app_version'] ?? "";
                // $data['ios_app_version'] = $settings['ios_app_version'] ?? "";
                // $data['force_app_update'] = $settings['force_app_update'] ?? "";
                // $data['app_maintenance'] = $settings['app_maintenance'] ?? "";
                // $data['session_year'] = $calender;
                // $data['school_name'] = $settings['school_name'] ?? "";
                // $data['school_tagline'] = $settings['school_tagline'] ?? "";
                // $data['teacher_app_link'] = $settings['teacher_app_link'] ?? "";
                // $data['teacher_ios_app_link'] = $settings['teacher_ios_app_link'] ?? "";
                // $data['teacher_app_version'] = $settings['teacher_app_version'] ?? "";
                // $data['teacher_ios_app_version'] = $settings['teacher_ios_app_version'] ?? "";
                // $data['teacher_force_app_update'] = $settings['teacher_force_app_update'] ?? "";
                // $data['teacher_app_maintenance'] = $settings['teacher_app_maintenance'] ?? "";
            } elseif($request->type == "privacy_policy") {
              //  $data = $settings[$request->type] ?? "";
                $data = "<h1>Privacy Policy for Antalya Swat</h1> <p>At antalya swat school, accessible from https://school.antalyaswat.com.pk, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by antalya swat school and how we use it.</p> <p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</p> <p>This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in antalya swat school. This policy is not applicable to any information collected offline or via channels other than this website. Our Privacy Policy was created with the help of the <a href=\"https://www.termsfeed.com/privacy-policy-generator/\">TermsFeed Free Privacy Policy Generator</a>.</p> <h2>Consent</h2> <p>By using our website, you hereby consent to our Privacy Policy and agree to its terms.</p> <h2>Information we collect</h2> <p>The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.</p> <p>If you contact us directly, we may receive additional information about you such as your name, email address, phone number, the contents of the message and/or attachments you may send us, and any other information you may choose to provide.</p> <p>When you register for an Account, we may ask for your contact information, including items such as name, company name, address, email address, and telephone number.</p> <h2>How we use your information</h2> <p>We use the information we collect in various ways, including to:</p> <ul> <li>Provide, operate, and maintain our website</li> <li>Improve, personalize, and expand our website</li> <li>Understand and analyze how you use our website</li> <li>Develop new products, services, features, and functionality</li> <li>Communicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes</li> <li>Send you emails</li> <li>Find and prevent fraud</li> </ul> <h2>Log Files</h2> <p>antalya swat school follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users' movement on the website, and gathering demographic information.</p> <h2>Advertising Partners Privacy Policies</h2> <p>You may consult this list to find the Privacy Policy for each of the advertising partners of antalya swat school.</p> <p>Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on antalya swat school, which are sent directly to users' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit.</p> <p>Note that antalya swat school has no access to or control over these cookies that are used by third-party advertisers.</p> <h2>Third Party Privacy Policies</h2> <p>antalya swat school's Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options.</p> <p>You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers' respective websites.</p> <h2>CCPA Privacy Rights (Do Not Sell My Personal Information)</h2> <p>Under the CCPA, among other rights, California consumers have the right to:</p> <p>Request that a business that collects a consumer's personal data disclose the categories and specific pieces of personal data that a business has collected about consumers.</p> <p>Request that a business delete any personal data about the consumer that a business has collected.</p> <p>Request that a business that sells a consumer's personal data, not sell the consumer's personal data.</p> <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.</p> <h2>GDPR Data Protection Rights</h2> <p>We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:</p> <p>The right to access &ndash; You have the right to request copies of your personal data. We may charge you a small fee for this service.</p> <p>The right to rectification &ndash; You have the right to request that we correct any information you believe is inaccurate. You also have the right to request that we complete the information you believe is incomplete.</p> <p>The right to erasure &ndash; You have the right to request that we erase your personal data, under certain conditions.</p> <p>The right to restrict processing &ndash; You have the right to request that we restrict the processing of your personal data, under certain conditions.</p> <p>The right to object to processing &ndash; You have the right to object to our processing of your personal data, under certain conditions.</p> <p>The right to data portability &ndash; You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.</p> <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.</p> <h2>Children's Information</h2> <p>Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.</p> <p>antalya swat school does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</p>";
            } elseif($request->type == "about_us") {
              //  $data = $settings[$request->type] ?? "";
                $data =  "<h1><strong>About US</strong></h1> <p>Antalya Schools & Colleges is committed to excellence. It promotes multicultural environment and values derived from the glorious religion of Islam that binds Pakistan and Turkey together in Muslim brotherhood. Antalya aspires to become a leading institute which educates, enlightens and develops the new generation by instilling in them an innovation-driven vision and insight. This will enable them to become responsible, disciplined citizens of integrity.</p>";
            } elseif($request->type == "contact_us") {
              //  $data = $settings[$request->type] ?? "";
                $data = "<h1><strong>Contact Us</strong></h1> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat egestas leo, id tincidunt velit sodales vel. Sed eget orci felis. Suspendisse luctus tellus et nisl efficitur, sit amet mollis ex luctus. Nunc sem neque, condimentum at urna ut, viverra volutpat quam. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla tempor nunc sed elementum mattis. Sed sit amet cursus lorem. Aliquam aliquam elit non ligula pretium, vel volutpat tortor ullamcorper. Nulla rutrum tortor id libero mollis ullamcorper. In ut rhoncus dui. Ut interdum sem porttitor, eleifend justo nec, egestas est. Phasellus id venenatis lectus. Suspendisse pulvinar, odio ac pellentesque fermentum, nibh erat commodo tortor, in sagittis enim lorem vitae velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur faucibus id nisl vel auctor. Ut varius elit vitae purus cursus, vitae commodo ex hendrerit. Morbi vestibulum libero nisi, vitae porttitor augue porta pulvinar. Nam scelerisque dignissim leo, at tincidunt risus imperdiet at. Nullam congue, lorem in fringilla laoreet, lacus est vehicula tellus, vitae cursus arcu magna eu purus. In tempor pulvinar blandit. Fusce risus massa, laoreet non ex a, efficitur eleifend est. Integer elementum ornare rutrum. Donec blandit interdum hendrerit.</p>";
            
            } elseif($request->type == "terms_condition") {
              //  $data = $settings[$request->type] ?? "";
                $data = "<h2><strong>Terms and Conditions</strong></h2> <p>Welcome to antalya swat school!</p> <p>These terms and conditions outline the rules and regulations for the use of antalya swat's Website, located at https://school.antalya swat.com.pk/.</p> <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use antalya swat school if you do not agree to take all of the terms and conditions stated on this page.</p> <p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: \"Client\", \"You\" and \"Your\" refers to you, the person log on this website and compliant to the Company&rsquo;s terms and conditions. \"The Company\", \"Ourselves\", \"We\", \"Our\" and \"Us\", refers to our Company. \"Party\", \"Parties\", or \"Us\", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client&rsquo;s needs in respect of provision of the Company&rsquo;s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p> <h3><strong>Cookies</strong></h3> <p>We employ the use of cookies. By accessing antalya swat school, you agreed to use cookies in agreement with the antalya swat's Privacy Policy.</p> <p>Most interactive websites use cookies to let us retrieve the user&rsquo;s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p> <h3><strong>License</strong></h3> <p>Unless otherwise stated, antalya swat and/or its licensors own the intellectual property rights for all material on antalya swat school. All intellectual property rights are reserved. You may access this from antalya swat school for your own personal use subjected to restrictions set in these terms and conditions.</p> <p>You must not:</p> <ul> <li>Republish material from antalya swat school</li> <li>Sell, rent or sub-license material from ite ch school</li> <li>Reproduce, duplicate or copy material from antalya swat school</li> <li>Redistribute content from antalya swat school</li> </ul> <p>This Agreement shall begin on the date hereof. Our Terms and Conditions were created with the help of the <a href=\"https://www.termsfeed.com/terms-conditions-generator/\">TermsFeed Free Terms and Conditions Generator</a>.</p> <p>Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. antalya swat does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of antalya swat,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, antalya swat shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</p> <p>antalya swat reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</p> <p>You warrant and represent that:</p> <ul> <li>You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</li> <li>The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</li> <li>The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</li> <li>The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</li> </ul> <p>You hereby grant antalya swat a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.</p> <h3><strong>Hyperlinking to our Content</strong></h3> <p>The following organizations may link to our Website without prior written approval:</p> <ul> <li>Government agencies;</li> <li>Search engines;</li> <li>News organizations;</li> <li>Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</li> <li>System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.</li> </ul> <p>These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party&rsquo;s site.</p> <p>We may consider and approve other link requests from the following types of organizations:</p> <ul> <li>commonly-known consumer and/or business information sources;</li> <li>dot.com community sites;</li> <li>associations or other groups representing charities;</li> <li>online directory distributors;</li> <li>internet portals;</li> <li>accounting, law and consulting firms; and</li> <li>educational institutions and trade associations.</li> </ul> <p>We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of antalya swat; and (d) the link is in the context of general resource information.</p> <p>These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party&rsquo;s site.</p> <p>If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to antalya swat. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</p> <p>Approved organizations may hyperlink to our Website as follows:</p> <ul> <li>By use of our corporate name; or</li> <li>By use of the uniform resource locator being linked to; or</li> <li>By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party&rsquo;s site.</li> </ul> <p>No use of antalya swat's logo or other artwork will be allowed for linking absent a trademark license agreement.</p> <h3><strong>iFrames</strong></h3> <p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p> <h3><strong>Content Liability</strong></h3> <p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p> <h3><strong>Your Privacy</strong></h3> <p>Please read Privacy Policy</p> <h3><strong>Reservation of Rights</strong></h3> <p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it&rsquo;s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p> <h3><strong>Removal of links from our website</strong></h3> <p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p> <p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p> <h3><strong>Disclaimer</strong></h3> <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p> <ul> <li>limit or exclude our or your liability for death or personal injury;</li> <li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li> <li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li> <li>exclude any of our or your liabilities that may not be excluded under applicable law.</li> </ul> <p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p> <p>As long as the websi                     te and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</p>";
            }
            $response = array(
                'error' => false,
                'message' => "Data Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    protected function forgotPassword(Request $request) {
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required|email"
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }

        try {
            $response = Password::sendResetLink($input);
            if ($response == Password::RESET_LINK_SENT) {
                $response = array(
                    'error' => false,
                    'message' => "Forgot Password email send successfully",
                    'code' => 200,
                );
            } else {
                $response = array(
                    'error' => true,
                    'message' => "Cannot send Reset Password Link.Try again later.",
                    'code' => 108,
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    protected function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|between:8,12',
            'new_confirm_password' => 'same:new_password',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }

        try {
            $user = $request->user();
            if (Hash::check($request->current_password, $user->password)) {
                $user->update(['password' => Hash::make($request->new_password)]);
                $response = array(
                    'error' => false,
                    'message' => "Password Changed successfully.",
                    'code' => 200,
                );
            } else {
                $response = array(
                    'error' => true,
                    'message' => "Invalid Password",
                    'code' => 105,
                );
            }
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }



public function getSchool(Request $request) {
    try {
       // $session_year = getSettings('session_year');
        //$session_year_id = $session_year['session_year'];

        //$data = SessionYear::find($session_year_id);
        $data=[];
        $data[]=[
            'id'=>1,
            'name'=>'Injazat ',
            'url'=>'https://abasyn.sfsc.edu.pk/api/'
        ];
        $data[]=[
            'id'=>2,
            'name'=>'Test Company',
            'url'=>'http://192.168.100.70/esms/public/api/'
        ];
        // $data[]=[
        //     'id'=>3,
        //     'name'=>'Swat3',
        //     'url'=>'https://lhss.sfsc.edu.pk/api/'
        // ];
        // $data[]=[
        //     'id'=>4,
        //     'name'=>'Swat4',
        //     'url'=>'https://lhss.sfsc.edu.pk/api/'
        // ];
       
        $response = array(
            'error' => false,
            'message' => "Schools Fetched Successfully",
            'data' => $data,
            'code' => 200,
        );
    } catch (\Exception $e) {
        $response = array(
            'error' => true,
            'message' => 'error_occurred',
            'code' => 103,
        );
    }
    return response()->json($response);
}
}