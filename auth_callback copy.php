<?php
// callback.php

require 'vendor/autoload.php'; // Ensure you've run `composer require guzzlehttp/guzzle`

$client = new GuzzleHttp\Client();

$clientId = '3MVG9sG9Z3Q1Rlbcr_PEobEWR8ui0RXxykFsn0vaP0TgkMbhX9SUnbSMgewpAJwzApTQfMxlx1GcCaupgWd2d';
$clientSecret = '007F7E053525C3281F77905F26C134086374E5E4A0ED8E9970C9BA00626077E9';
$redirectUri = 'https://beta.integrations.chasedatacorp.com/oauth2/callback'; // Must match exactly the Redirect URI in your Salesforce app settings

// if (isset($_GET['code'])) {
$code = 'aPrx8yPWn0Ctxzm_xwVjIKfCd67yec.drfLxMBzOehpm5oAxL7DXRq7gGiLhmMmDiyey8u7igA%3D%3D';
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
    echo($accessToken);
    echo($instanceUrl);
    /*
    $headers = [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type'  => 'text/xml;charset=UTF-8',
    ];
    
    // Example dynamic values (should be replaced with actual dynamic values)
    $objectApiName = "Task";
    $workflowName = "WorkflowName";
    $endpointUrl = "https://beta.taxengine.chasedatacorp.com/endpoint";

    $xmlBody = '<?xml version="1.0" encoding="UTF-8"?>';
    $xmlBody .= '<env:Envelope xmlns:xsd="http://www.w3.org/2001/XMLSchema"';
    $xmlBody .= '              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
    $xmlBody .= '              xmlns:env="http://schemas.xmlsoap.org/soap/envelope/">';
    $xmlBody .= '  <env:Body>';
    $xmlBody .= '    <createMetadata xmlns="http://soap.sforce.com/2006/04/metadata">';
    $xmlBody .= '      <metadata xsi:type="Workflow">';
    $xmlBody .= '        <fullName>' . escapeXml($objectApiName . '.' . $workflowName) . '</fullName>';
    $xmlBody .= '        <active>true</active>';
    $xmlBody .= '        <workflowActions>';
    $xmlBody .= '          <actions xsi:type="WorkflowOutboundMessage">';
    $xmlBody .= '            <fullName>' . escapeXml($objectApiName . '.' . $workflowName . '_OutboundMessage') . '</fullName>';
    $xmlBody .= '            <endpointUrl>' . escapeXml($endpointUrl) . '</endpointUrl>';
    // Repeat this line with different field names, properly escaped
    $xmlBody .= '            <fields>FirstName__c</fields>';
    $xmlBody .= '            <fields>LastName__c</fields>';
    $xmlBody .= '            <name>OutboundMessageName</name>';
    $xmlBody .= '            <useDeadLetterQueue>false</useDeadLetterQueue>';
    $xmlBody .= '            <protected>false</protected>';
    $xmlBody .= '            <integrationUser>IntegrationUserName</integrationUser>';
    $xmlBody .= '          </actions>';
    $xmlBody .= '        </workflowActions>';
    $xmlBody .= '        <rules>';
    $xmlBody .= '          <fullName>' . escapeXml($objectApiName . '.' . $workflowName . '_Rule') . '</fullName>';
    $xmlBody .= '          <criteriaItems>';
    $xmlBody .= '            <field>FieldToEvaluate__c</field>';
    $xmlBody .= '            <operation>equals</operation>';
    $xmlBody .= '            <value>ValueToMatch</value>';
    $xmlBody .= '          </criteriaItems>';
    $xmlBody .= '          <actions>';
    $xmlBody .= '            <type>OutboundMessage</type>';
    $xmlBody .= '            <name>' . escapeXml($objectApiName . '.' . $workflowName . '_OutboundMessage') . '</name>';
    $xmlBody .= '          </actions>';
    $xmlBody .= '          <active>true</active>';
    $xmlBody .= '          <triggerType>onAllChanges</triggerType>';
    $xmlBody .= '        </rules>';
    $xmlBody .= '      </metadata>';
    $xmlBody .= '    </createMetadata>';
    $xmlBody .= '  </env:Body>';
    $xmlBody .= '</env:Envelope>';

    $response = $client->post($instanceUrl . '/services/Soap/m/60.0', [
        'headers' => $headers,
        'body'    => $xmlBody,
    ]);
    */
    // Handle response
} catch (Exception $e) {
    // Handle error
    echo 'Error: ',  $e->getMessage(), "\n";
}

