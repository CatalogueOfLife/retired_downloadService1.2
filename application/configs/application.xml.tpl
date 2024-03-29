<?xml version="1.0" encoding="UTF-8"?>
<application xmlns:zf="http://framework.zend.com/xml/zend-config-xml/1.0/">

	<!-- 
		WARNING: Watch out when formatting this file, because all tags
		specifying some file or path must be on a single line without spaces. 
	  -->

	<!--  P R O D U C T I O N  -->
	<production>

		<phpSettings>
			<display_startup_errors>0</display_startup_errors>
			<display_errors>0</display_errors>
			<error_reporting>0</error_reporting>
		</phpSettings>

		<includePaths>
			<library><zf:const zf:name="APPLICATION_PATH" />/../library</library>
			<AC_DCA_Exporter>@PATH_TO_AC_DCA_EXPORTER@</AC_DCA_Exporter>
			<AC_DCA_ExporterBaseUrl>@BASE_URL_TO_AC_DCA_EXPORTER@</AC_DCA_ExporterBaseUrl>
			<PHPLocation>@PHP_LOCATION@</PHPLocation>
			<basePath>@BASE_PATH@</basePath>
		</includePaths>

		<bootstrap>
			<path><zf:const zf:name="APPLICATION_PATH" />/Bootstrap.php</path>
			<class>Bootstrap</class>
		</bootstrap>

		<resources>
			<!-- FRONT CONTROLLER -->
			<frontController>
				<moduleDirectory><zf:const zf:name="APPLICATION_PATH" />/modules</moduleDirectory>
				<defaultModule>api</defaultModule>
				<moduleControllerDirectoryName>controllers</moduleControllerDirectoryName>
				<params>
					<displayExceptions>1</displayExceptions>
				</params>
				<!--
					You need to specify the baseUrl when accessing your application
					through an Apache alias or when your virtual host's document root
					does not point to the public directory. You will also have to set
					the RewriteBase in .htaccess then.
				-->
				<baseUrl>@BASE_URL@</baseUrl>
			</frontController>

			<!-- LAYOUT -->
			<layout>
				<layout>layout</layout>
				<layoutPath><zf:const zf:name="APPLICATION_PATH" />/layouts/scripts</layoutPath>
			</layout>

			<!-- LOGGING -->
			<log>
				<stream>
					<writerName>Firebug</writerName>
				</stream>
			</log>

			<!-- LOCALE -->
			<locale>
				<default>en_US</default>
			</locale>

			<!-- DATABASE -->
			<multidb>
				<db1>
					<adapter>pdo_mysql</adapter>
					<host>@DBHOST@</host>
					<username>@DBUSER@</username>
					<password>@DBPASS@</password>
					<dbname>@DBNAME@</dbname>
				</db1>
			</multidb>
		</resources>

		<constants>
			<version>
				<major>0</major>
				<minor>001</minor>
			</version>
		</constants>
	</production>


	<!--  T E S T  -->
	<test zf:extends="production" />


	<!--  D E V E L O P M E N T  -->
	<development zf:extends="production">
		<phpSettings>
			<display_startup_errors>1</display_startup_errors>
			<display_errors>1</display_errors>
			<error_reporting>1</error_reporting>
		</phpSettings>
		<resources>
			<!-- FRONT CONTROLLER -->
			<frontController>
				<params>
					<displayExceptions>1</displayExceptions>
				</params>
				<!--
					You need to specify the baseUrl when accessing your application
					through an Apache alias or when your virtual host's document root
					does not point to the public directory. You will also have to set
					the RewriteBase in .htaccess then.
				-->
				<baseUrl></baseUrl>
			</frontController>
		</resources>
	</development>


</application>

