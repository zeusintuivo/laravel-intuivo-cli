@echo off
set CATALINA_OPTS=-Djavax.net.ssl.trustStore="%CATALINA_HOME%\security\pstruststore.jks" 
set CATALINA_OPTS=%CATALINA_OPTS% -Djavax.net.ssl.trustStorePassword="password" 
set CATALINA_OPTS=%CATALINA_OPTS% -Djava.net.preferIPv4Stack=true

rem Make sure this value matches the entity-id element in the saml tag in your plugin XML, as well as the idpEntityID suffix and partner value in the idpMetadataURL
set CATALINA_OPTS=%CATALINA_OPTS% -DspEntityID="springsecuritysaml"

rem Make sure these values match the IdP Entity ID and metadata URL in the PowerSchool Plugin Management Dashboard
set CATALINA_OPTS=%CATALINA_OPTS% -DidpEntityID="https://localhost/springsecuritysaml"
set CATALINA_OPTS=%CATALINA_OPTS% -DidpMetadataURL="https://localhost/powerschool-saml-sso/metadata/customIDPMetadataAction.action?partner=springsecuritysaml"

goto end

:end