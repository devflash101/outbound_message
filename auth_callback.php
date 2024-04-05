<?php
// callback.php

require 'vendor/autoload.php'; // Ensure you've run `composer require guzzlehttp/guzzle`

$client = new GuzzleHttp\Client();

$clientId = '3MVG9sG9Z3Q1Rlbcr_PEobEWR8ui0RXxykFsn0vaP0TgkMbhX9SUnbSMgewpAJwzApTQfMxlx1GcCaupgWd2d';
$clientSecret = '007F7E053525C3281F77905F26C134086374E5E4A0ED8E9970C9BA00626077E9';
$redirectUri = 'https://beta.integrations.chasedatacorp.com/oauth2/callback'; // Must match exactly the Redirect URI in your Salesforce app settings

// if (isset($_GET['code'])) {
$code = 'aPrx8yPWn0Ctxzm_xwVjIKfCd2y_w1DdVcr62B7aRlWh5HdQmeOdU5.A4JNmVYAcahOZAPSxpQ%3D%3D';
    // echo $code;
// Simple function to escape XML characters
function escapeXml($value) {
    return htmlspecialchars($value, ENT_XML1, 'UTF-8');
}

try {
    $response = $client->post("https://login.salesforce.com/services/oauth2/token?grant_type=authorization_code&code=$code&client_id=$clientId&client_secret=$clientSecret&redirect_uri=$redirectUri");

    $body = json_decode((string) $response->getBody(), true);

    echo "<p>Access Token: " . $body['access_token'] . "</p>";
    echo "<p>Instance Url: " . $body['instance_url'] . "</p>";
    // Here you might want to redirect the user or display a success message


    $accessToken = $body['access_token'];
    $instanceUrl = $body['instance_url'];

    $wsdl = './metadata.xml';

    // Initialize the SOAP client using the WSDL and the session from OAuth
    $client = new SoapClient($wsdl, [
        'trace' => 1,
        'exception' => 0,
        'typemap' => [
            [
                'type_ns' => 'http://www.w3.org/2001/XMLSchema-instance',
                'type_name' => 'type',
                'from_xml' => function($xml) { /* ... */ },
                'to_xml' => function($value) { /* ... */ },
            ],
        ],
    ]);

    // Set the location to the Salesforce Metadata API endpoint
    $client->__setLocation($instanceUrl . '/services/Soap/m/60.0');

    // Create a new session header with the session ID
    $sessionHeader = new SoapHeader('http://soap.sforce.com/2006/04/metadata', 'SessionHeader', ['sessionId' => $accessToken]);
    $client->__setSoapHeaders([$sessionHeader]);

    
    // Define the outbound message metadata
    $outboundMessageMetadata = [
        // 'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
        'xsi:type' => 'WorkflowOutboundMessage', // Define the xsi:type for the metadata element
        'fullName' => 'Lead.Send_New_Lead5_OutboundMessage', // Replace with your actual Workflow and Outbound Message names
        'apiVersion' => 60.0, // Replace with the API version of your org
        'enabled' => true,
        'integrationUser' => 'g.sandovalcd@chasedatacorp.com', // Replace with the email of the user for outbound messaging
        'endpointUrl' => 'https://your.endpoint.url/endpoint', // Replace with your actual endpoint URL
        'description' => 'Outbound message triggered by MyWorkflowRule',
        'contact' => '', // Optionally specify a contact
        'fields' => ['FirstName', 'LastName', 'Email'], // Replace with the fields you want to send
        'name' => 'Send_New_Lead5_OutboundMessage', // The name of the outbound message
        'includeSessionId' => true,
        'protected' => false,
        // Other outbound message configurations...
    ];
    
    // Define the workflow rule metadata
    $workflowRuleMetadata = [
        // 'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
        'xsi:type' => 'WorkflowRule', // Define the xsi:type for the metadata element
        'fullName' => 'Lead.Send New Lead5', // Replace with your actual Object and Workflow Rule names
        'active' => true,
        'description' => 'This workflow rule triggers an outbound message',
        'formula' => 'True', // Replace with your actual trigger formula
        'triggerType' => 'onCreateOrTriggeringUpdate', // Define when this workflow rule should trigger
        'workflowActions' => [
            (object)[
                // 'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
                'xsi:type' => 'WorkflowActionReference', // Define the xsi:type for the action element
                'type' => 'OutboundMessage', // Specify the type of action
                'name' => 'Send_New_Lead5_OutboundMessage', // Reference the full name of the outbound message
            ],
        ],
        // Other workflow rule configurations...
    ];

    // Assuming $client is your initialized SoapClient for Salesforce

    // Convert the outbound message metadata to an object and wrap it with SoapVar
    $outboundMessageMetadataObject = (object)$outboundMessageMetadata;
    $outboundMessageSoapVar = new SoapVar($outboundMessageMetadataObject, SOAP_ENC_OBJECT, 'WorkflowOutboundMessage', 'http://soap.sforce.com/2006/04/metadata');

    // Convert the workflow rule metadata to an object
    $workflowRuleMetadataObject = (object)$workflowRuleMetadata;
    // Specifically handling the workflowActions to ensure proper type encapsulation
    $workflowRuleMetadataObject->workflowActions = [
        new SoapVar((object)$workflowRuleMetadata['workflowActions'], SOAP_ENC_OBJECT, 'WorkflowActionReference', 'http://soap.sforce.com/2006/04/metadata')
    ];

    // Wrap the workflow rule metadata object with SoapVar
    $workflowRuleSoapVar = new SoapVar($workflowRuleMetadataObject, SOAP_ENC_OBJECT, 'WorkflowRule', 'http://soap.sforce.com/2006/04/metadata');

    try {
        // Attempt to create the metadata in Salesforce using the SOAP API
        $response = $client->createMetadata(['metadata' => [$workflowRuleSoapVar, $outboundMessageSoapVar]]);
        print_r($response);
    } catch (SoapFault $e) {
        echo "SOAP Exception: " . $e->getMessage();
    }


    // try {
    //     $response = $client->createMetadata(['metadata' => [$workflowRuleMetadata, $outboundMessageMetadata]]);
    //     print_r($response);
    // } catch (SoapFault $e) {
    //     // Handle the SOAP Fault
    //     echo "Exception: " . $e->getMessage();
    // }

} catch (Exception $e) {
    // Handle error
    echo 'Error: ',  $e->getMessage(), "\n";
}

