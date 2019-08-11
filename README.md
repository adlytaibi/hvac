# HVAC Thermostat data graph

This is a php-based code that provides a web-interface to graph data collected from thermostat. This works for "Radio Thermostat CT80", I don't see why it wouldn't work for any other unit. As long as the thermostat provides a RESTful API, the collection scripts can be tailored to fit your own.
The workflow is simple, you provide the IP address or hostname of you thermostat. RESTful API calls executed by means of a cron job on the host server to read from the Thermostat, the collected data is stored in a local SQLite database. The graph uses Chart.js to display the collected data read from the database.

  ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/functional_block_diagram.svg?sanitize=true)

You may choose to host the code on your own webserver without Docker. Simply copy the content of `hvac/web/src/` and ignore the rest.

## Pre-requisites

* git
* docker
* docker-compose

## Installation

1. Clone this:

    ```
    git clone https://github.com/adlytaibi/hvac
    ```

    ```
    cd hvac
    ```

2. SSL certificates

    ```
    mkdir web/sslkeys
    ```

    1. Self-sign your own certificates: (modify `web` to match your server)

        ```
        openssl req -x509 -nodes -newkey rsa:4096 -keyout web/sslkeys/host.key -out web/sslkeys/host.pem -days 365 -subj "/C=CA/ST=Ontario/L=Ottawa/O=Home/OU=Automation/CN=web"
        ```

3. docker-compose

    ```
    docker-compose up -d
    ```

4. The login page can be accessed using the URL below:

    ```
    https://<IP_address>
    ```
    (or if accessing from the same guest https://localhost)

    Needed PHP modules are checked as well as ability to write to the database directory.

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/01_Initial_page.png)

5. If you're ready to connect to your Thermostat, enter the hostname or IP address:

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/02_Thermostat_setup.png)

6. Or you can try out the application with randomly generated data in a demonstration mode:

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/02_Demo_setup.png)

7. Data points will be generated for the number of months selected. The time to process depends on how powerful your server is.

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/04_Demo_progress.gif)

8. `Monthly view` averages are calculated for inside/outside temperatures and humidity

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/05_Demo_chart_monthly.png)

9. Selecting a month will take you to a `Weekly view` with average values per week

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/06_Demo_chart_weekly.png)

10. Selecting a week will take you to a `Daily view` with average values for per day

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/07_Demo_chart_daily.png)

11. Selecting a day will take you to an `Hourly view` which represents raw values as they were collected as well as showing the heating/cooling runtime of the HVAC system

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/08_Demo_chart_hourly.png)

12. At any time you can jump to `Today's view` with the home button. You can quickly jump to next/previous days

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/09_Demo_chart_today.png)

13. Finally, the demonstration data can be deleted when you're done staring at it. This will bring you back to the setup view as in (5).

    ![](https://raw.githubusercontent.com/adlytaibi/ss/master/hvac/10_Demo_chart_delete.png)

14. Cron file is conveniently provided here `hvac/web/cron/hvac` to run from the Docker host. Drop the Docker section of the command if you're running without Docker.

## Further reading
* [Docker Compose](https://docs.docker.com/compose/)
* [Apache](https://httpd.apache.org/)
* [PHP](https://www.php.net/)
* [SQLite](https://sqlite.org/)
* [Bootstrap](https://getbootstrap.com/)
* [jQuery](https://jquery.com/)
* [Chart.js](https://chartjs.org/)

## Notes

