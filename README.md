Small Weather Project
==================================

# Need to run #

* run `docker compose -f docker-compose.yml -p weather up -d composer` to run docker container
* after docker up application run at `http://localhost:8070`
* to use API create get your token at https://www.weatherapi.com and apply him to `.env` file

# Endpoints #

* Average Temperature - check average temperature from past by cities and date
  * Endpoint: http://localhost:8070/average_temp
  * Method: POST
  * Request:
    * Parameters: 
      * cities: array
      * date: string
    * Example:
      * ```{"date":"2024-07-01","cities": ["Poznan","Gdansk","Warszawa"]}```
  * Response:
    * Parameters:
      * average_temp: float
    * Example:
      * ```{"average_temp": 18.7 }```
