Sandbox Server Information:
Below is the link to the ISV Partner Sandbox Server:
To log into the Student Portal please use the following URL 
/​​
http://partner11.powerschool.com​ 
Login: ag1student60
Password: student
 
In order to access the Administrative Portal, you must to append the URL with:  /admin​
​
​http://partner11.powerschool.com/admin
 
Here you will only see one login box, in order to login as the administrator the logon protocol is as follows:
Username(semicolon)password

Example: MAbram1;aghs1
​   ​      
To log into PowerSchool/PowerTeacher you must to append the URL with:  /teachers

(http://partner11.powerschool.com/teachers)  
 
Login: 20aghs1
​ Password:aghs1
 
You can create an account to log into the Parent Portal by taking the following steps:
1.     Login to the admin portal and select a student
2.     Click the Access Accounts link from the main navigation menu (left hand side of the screen)
3.     The access Accounts screen will appear, make sure enable parent access is checked, modify and/or make note of the access ID and access password (default is parent)
4.     Logout and Go to the public portal, click the create account button
5.     Fill out all the fields on the create parent account screen, you will use the student’s name and the access ID and access password from step 3 above
6.     Use this account to login as a parent on the parent portal

Please note the attached the demo admin, teacher, and student logins. There is a long list of Id's here, these are all of the test student/parent login's in the Sandbox Server and you can use any of them.  Please let me know if you have any questions or run into any access issues with the server.

​PowerSchool Integration Consulting: ​
​When you are ready to starting building integration with PowerSchool, we have specialists that can address any questions that you may have throughout this process. ​


API Partner Developer Documentation:
​Information about our API and integration technology is available  on PowerSource at: https://powersource.pearsonschoolsystems.com/developer/ ​ and in the API Labs area in located at  https://powersource.pearsonschoolsystems.com/f/powerschool_api_lab​ ​. ​




Data Configuration for rest-api-phi-example {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/

	Client ID "c6910f0a-29ee-420a-9586-1beab096c7a4"
	Client Secret "2202cf6a-3c8d-48f6-b62f-dbdf1d44eaca"
	Current OAuth Token "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6"  (Expires 9/12/2015, 4:01:38 PM)

    WORKS {

        node accesstoken.example.js "https://partner11.powerschool.com"  "c6910f0a-29ee-420a-9586-1beab096c7a4"  "2202cf6a-3c8d-48f6-b62f-dbdf1d44eaca";

        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/district"  "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";
        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/district/addresses"  "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";
        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/student/11758"  "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";

        node enroll.example.js "https://partner11.powerschool.com"    "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";
    }


    DOESNOT WORK: {
    	//HTTP 404 Not Found
    	 node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/district/addresses"  "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";
      
       	//<errorMessage><message>HTTP 405 Method Not Allowed</message></errorMessage>
        node resource.example.js "https://partner11.powerschool.com"  "/ws/schema/query/com.company.product.school.school_calendar"  "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";
        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/district/ethnicity_race_decline_to_specify"  "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";
        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/query/com.pearson.core.attendance.attendance_code_by_school_date"  "f9dd30c1-234a-4895-b0a3-2af36d0aa0d6";

    }
}



Data Configuration
for rest - api - example {

    /*
    OAuth Credentials

    This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

    This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
    */
    Client ID "fde22037-73ab-41f6-a105-eed32c04046c"
    Client Secret "5e980e3f-d38e-40a5-b105-40a0dd8a5555"
    Current OAuth Token "487d841e-20d2-4647-a09f-b1c2414d8580" (Expires 9 / 11 / 2015, 8: 17: 28 AM)

    WORKS {

        node accesstoken.example.js "https://partner11.powerschool.com"  "fde22037-73ab-41f6-a105-eed32c04046c"  "5e980e3f-d38e-40a5-b105-40a0dd8a5555";
        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/district"  "487d841e-20d2-4647-a09f-b1c2414d8580";
        node enroll.example.js "https://partner11.powerschool.com"    "487d841e-20d2-4647-a09f-b1c2414d8580";
    }


    DOESNOT WORK: {
    	//<errorMessage><message>HTTP 405 Method Not Allowed</message></errorMessage>
        node resource.example.js "https://partner11.powerschool.com"  "/ws/schema/query/com.company.product.school.school_calendar"  "487d841e-20d2-4647-a09f-b1c2414d8580";

    }
}



Data Configuration
for Tariq Example {


    OAuth Credentials

    This information needs to be provided to authorized third - party vendors to facilitate their access to PowerSchool.

    This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.

    ￼
    Client ID "0b7895db-5959-4ff1-939e-24f2089c5191"
    Client Secret "119a773e-862a-4c9c-8f8a-02b2790aa38a"
    Current OAuth Token "b54bda19-a6c0-4cf5-a202-51ee1853338a" (Expires 9 / 9 / 2015, 12: 57: 00 PM)

    WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com"  "0b7895db-5959-4ff1-939e-24f2089c5191"  "119a773e-862a-4c9c-8f8a-02b2790aa38a";
        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/district"  "b54bda19-a6c0-4cf5-a202-51ee1853338a";
        node enroll.example.js "https://partner11.powerschool.com"    "b54bda19-a6c0-4cf5-a202-51ee1853338a";
    }

    DOESNOT WORK: {

    }

}


Data Configuration
for DeansList Sync Plugin
for PowerSchool {

    /*
    OAuth Credentials

    This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

    This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
    */
    ￼
    Client ID "d9e801fa-8854-4b2d-8f30-6c1640551871"
    Client Secret "b36f4121-807c-4046-8418-dbb75fe3e287"
    Current OAuth Token "ec849270-0fe8-4559-8e00-1dedbc5630c0" (Expires 9 / 10 / 2015, 3: 25: 44 PM)


    WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com"  "d9e801fa-8854-4b2d-8f30-6c1640551871"  "b36f4121-807c-4046-8418-dbb75fe3e287";
        node resource.example.js "https://partner11.powerschool.com"  "/ws/v1/district"  "ec849270-0fe8-4559-8e00-1dedbc5630c0";
        node enroll.example.js "https://partner11.powerschool.com"    "ec849270-0fe8-4559-8e00-1dedbc5630c0";
    }

    DOESNOT WORK: {

    }

}



Data Configuration
for EnrichingStudents {

    /*
    OAuth Credentials

    This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

    This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
    */
    ￼
    Client ID "784e6198-b1c2-44c9-afc8-6499aad8dd42"
    Client Secet "7081f222-c468-481b-be0f-c779b6d333b5"
    Current OAuth Token "f8591459-c8c9-44d0-990c-d10860ceeca8" (Expires 9 / 12 / 2015, 10: 08: 29 AM)

    WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "784e6198-b1c2-44c9-afc8-6499aad8dd42" "7081f222-c468-481b-be0f-c779b6d333b5";
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"  "f8591459-c8c9-44d0-990c-d10860ceeca8";
        node enroll.example.js "https://partner11.powerschool.com"   "f8591459-c8c9-44d0-990c-d10860ceeca8";
    }

    DOESNOT WORK: {

    }


}



Data Configuration for intoCareers CIS {


	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/
	￼
	Client ID "9e8495c3-8a4e-4efb-8c91-4de164e1b014"
	Client Secret "8e355fa9-e5dd-453c-b8d6-ab5530a469e1"
	Current OAuth Token '58b5eb3e-dc4c-40d7-9262-5c8dd3ae1d2f' (Expires Invalid Date)

    WORKS: {
        node accesstoken.example.js "https://partner11.powerschool.com" "9e8495c3-8a4e-4efb-8c91-4de164e1b014" "8e355fa9-e5dd-453c-b8d6-ab5530a469e1";
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district" '58b5eb3e-dc4c-40d7-9262-5c8dd3ae1d2f';
        node enroll.example.js "https://partner11.powerschool.com"  '58b5eb3e-dc4c-40d7-9262-5c8dd3ae1d2f';
    }

    DOESNOT WORK: {

    }


}


Data Configuration for intoCareers MT CIS {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/

	Client ID "a9f582a8-7f8b-4d8d-a4f1-ee7f574d7b32"
	Client Secret "39ecd6f4-1fa7-45f8-940a-154baa10e890"
	Current OAuth Token "2065c7eb-e5b3-4441-8326-57ce53019e24" (Expires 8/18/2015, 1:17:26 PM)

   WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "a9f582a8-7f8b-4d8d-a4f1-ee7f574d7b32" "39ecd6f4-1fa7-45f8-940a-154baa10e890";
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"   "2065c7eb-e5b3-4441-8326-57ce53019e24";
        node enroll.example.js "https://partner11.powerschool.com"    "2065c7eb-e5b3-4441-8326-57ce53019e24";
    }

    DOESNOT WORK: {

    }


}

Data Configuration for Naviance SSO {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/
	￼
	Client ID "f37db0ac-8a56-4bc5-b6d7-568067c75b50"
	Client Secret "d1291662-7eb9-48b4-a117-fbb87f18a13a"
	Current OAuth Token "008ed452-fc55-4b8d-b984-a354f4cf725d"  (Expires 9/10/2015, 8:27:54 AM)



   WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "f37db0ac-8a56-4bc5-b6d7-568067c75b50" "d1291662-7eb9-48b4-a117-fbb87f18a13a";
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"   "008ed452-fc55-4b8d-b984-a354f4cf725d";
        node enroll.example.js "https://partner11.powerschool.com"    "008ed452-fc55-4b8d-b984-a354f4cf725d";
    }

    DOESNOT WORK: {

    }


}

Data Configuration for Naviance SSO localhost {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/
	￼
	Client ID "39134cb8-a55f-4070-ac35-2a1dc1fd9b40"
	Client Secret "9deb0786-f471-480e-b9c4-028a4a8f36e3"
	Current OAuth Token "2333b7d4-4b1a-49a4-8a92-2d4b9672bcbf"  (Expires 8/28/2015, 1:52:59 PM)


   WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "39134cb8-a55f-4070-ac35-2a1dc1fd9b40" "9deb0786-f471-480e-b9c4-028a4a8f36e3"
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"   "2333b7d4-4b1a-49a4-8a92-2d4b9672bcbf";
        node enroll.example.js "https://partner11.powerschool.com"    "2333b7d4-4b1a-49a4-8a92-2d4b9672bcbf";
    }

    DOESNOT WORK: {

    }
}

Data Configuration for ParentSquare API Plugin {


	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/
	￼
	Client ID "c7b5c4d8-bca4-4497-b83a-40485e79a4c4"
	Client Secret "1c6a25cd-9014-4c70-90bc-ee97d249f9e9"
	Current OAuth Token "f4b012df-9ba8-4e9e-a78e-9ea3fa1f1503"  (Expires 9/12/2015, 10:49:09 AM)

   WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "c7b5c4d8-bca4-4497-b83a-40485e79a4c4" "1c6a25cd-9014-4c70-90bc-ee97d249f9e9"
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"  "f4b012df-9ba8-4e9e-a78e-9ea3fa1f1503";
        node enroll.example.js "https://partner11.powerschool.com"   "f4b012df-9ba8-4e9e-a78e-9ea3fa1f1503";
    }

    DOESNOT WORK: {

    }
}

Data Configuration for ParentSquare chime Plugin {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/
	￼
	Client ID "bee43dce-6e9c-45fa-8f43-dda1f72f4ced"
	Client Secret "8a466bb5-2e38-4a2d-83ee-59886ff1c8ba"
	Current OAuth Token "a587ab15-22e5-4d06-bf61-a9b492f177eb" (Expires 8/30/2015, 9:49:38 AM)

    WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "bee43dce-6e9c-45fa-8f43-dda1f72f4ced" "8a466bb5-2e38-4a2d-83ee-59886ff1c8ba"
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"   "a587ab15-22e5-4d06-bf61-a9b492f177eb"
        node enroll.example.js "https://partner11.powerschool.com"    "a587ab15-22e5-4d06-bf61-a9b492f177eb"
    }

    DOESNOT WORK: {

    }
}



Data Configuration for ParentSquare Custome Attribute {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/

	Client ID "b56b9ca4-8d07-477a-b4da-88c3c10ee400"
	Client Secret "b54fa26a-140f-4ce7-b50b-1c3204237e9e"
	Current OAuth Token "edcd49d4-9bcc-476d-baba-5bd7a035a4c6"  (Expires 9/1/2015, 12:14:35 AM)

    WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "b56b9ca4-8d07-477a-b4da-88c3c10ee400" "b54fa26a-140f-4ce7-b50b-1c3204237e9e"
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"   "edcd49d4-9bcc-476d-baba-5bd7a035a4c6" 
        node enroll.example.js "https://partner11.powerschool.com"    "edcd49d4-9bcc-476d-baba-5bd7a035a4c6" 
    }

    DOESNOT WORK: {

    }

}


Data Configuration for ParentSquare Dataload Plugin {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/
	￼
	Client ID "7dea2515-57fc-45dd-b162-c6e1dd6833a6"
	Client Secret "4054e05e-c5cb-41f5-be2e-127997f90815"
	Current OAuth Token "b1dd3b90-c21b-40c3-81d5-ec1f75199d11"  (Expires 9/9/2015, 3:02:00 PM)

    WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "7dea2515-57fc-45dd-b162-c6e1dd6833a6" "4054e05e-c5cb-41f5-be2e-127997f90815"
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"   "b1dd3b90-c21b-40c3-81d5-ec1f75199d11" 
        node enroll.example.js "https://partner11.powerschool.com"    "b1dd3b90-c21b-40c3-81d5-ec1f75199d11" 
    }

    DOESNOT WORK: {

    }

}

Data Configuration for Schoolnet {

	/*
	OAuth Credentials

	This information needs to be provided to authorized third-party vendors to facilitate their access to PowerSchool.

	This is sensitive data and should be kept secure to prevent unauthorized access to PowerSchool.
	*/

	Client ID "9fb3e4b0-5976-44c8-af20-771bdcd6a33b"
	Client Secret "f3788497-8498-4328-84e5-f5aed840027e"
	Current OAuth Token "c0bd9299-c1ed-4bb9-9b61-59e22b778cae"  (Expires 9/12/2015, 11:10:44 AM)


    WORKS: {

        node accesstoken.example.js "https://partner11.powerschool.com" "9fb3e4b0-5976-44c8-af20-771bdcd6a33b" "f3788497-8498-4328-84e5-f5aed840027e"
        node resource.example.js "https://partner11.powerschool.com" "/ws/v1/district"  "c0bd9299-c1ed-4bb9-9b61-59e22b778cae"
        node enroll.example.js "https://partner11.powerschool.com"   "c0bd9299-c1ed-4bb9-9b61-59e22b778cae"
    }

    DOESNOT WORK: {

    }
}



QUESTIONS: {
	


Question 1. Which tokens can we use ?
Question 2. Do tokens have a different level of access ?
Question 3. How do we know what kind of access has a token been granted ?
Question 4. From the list of plugins, there is huge list, which one do we use, or do we need a new one ?

"

https://partner11.powerschool.com/admin/pluginconsole/plugInConsole.action

"

	Plugin Management Dashboard

	Installed Plugins

	Install Resource Report
	 	Name	Version	Enable/Disable	
	1	AET OAuth Token Viewer	1.0		
	2	BETA PowerSchool API Developer Guide	1.7.0		
	3	* DeansList Sync Plugin for PowerSchool	1.3.2		
	4	* EnrichingStudents	1.0.0		
	5	* intoCareers CIS	1.0		
	6	* intoCareers MT CIS	1.0		
	7	* Naviance SSO	1.0		
	8	* Naviance SSO localhost	1.0		
	9	* ParentSquare API Plugin	1.2.3		
	10	* ParentSquare chime Plugin	1.0.2		
	11	* ParentSquare Custome Attribute	1.0		
	12	* ParentSquare Dataload Plugin	1.0.2		
	13	ParentSquare Local	1.0		
	14	Pearson Digital Learning Platforms	1.0		
	15	Remote Connection Manager	1.0.0		
	16	* rest-api-example	1.0.0		
	17	* rest-api-phi-example	1.0.0		
	18	* Schoolnet	0.0.1		
	19	* Tariq Example



}
