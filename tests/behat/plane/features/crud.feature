Feature: crud
  In order to test plane's CRUD functinlity
  As an API consumer
  I need to be able to post, get, put and delete planes using RESTFul protocol

Scenario: Post a plane to the service layer
  Given having the following list:
  | user  | make   | title   | model | description |
  | 1     | 1 | Skyhawk | 127   | The Cessna 172 Skyhawk is a four-seat, single-engine, high-wing fixed-wing aircraft made by the Cessna Aircraft Company. First flown in 1955, more Cessna 172s have been built than any other aircraft.|
  When I curl it as json using "POST" to "http://service.planeonline.local/plane" 
  Then I should get:
    """
{"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":1,"success":1,"fialed":0},"results":[{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"Skyhawk","model":"127","description":"The Cessna 172 Skyhawk is a four-seat, single-engine, high-wing fixed-wing aircraft made by the Cessna Aircraft Company. First flown in 1955, more Cessna 172s have been built than any other aircraft.","id":"???"}}]}
    """

Scenario: Post multiple plane to the service layer
  Given having the following list:
  | user  | make   | title   | model | description |
  | 2     | 3 | Skyhawk | 127 | The Cessna 172 001 |
  | 2     | 3 | Sky | 127 | The Cessna 172 002 |
  | 2     | 3 | Skyhawk | 127 | The Cessna 172 003 |
  When I curl it as json using "POST" to "http://service.planeonline.local/plane" 
  Then I should get:
    """
    {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":3,"success":2,"fialed":1},"results":[{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"Skyhawk","model":"127","description":"The Cessna 172 001","id":"???"}},{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":{"Validation messages":[{"title":"Minimum number of charcters for title is 4"}]}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"Skyhawk","model":"127","description":"The Cessna 172 003","id":"???"}}]}
    """

Scenario: Post multiple plane to the service layer as ATC
  Given having the following list:
  | user  | make   | title   | model | description |
  | 1     | 1 | t-01 | m01 | des-01 |
  | 1     | 1 | t-01 | m02 | des-02 |
  | 1     | 1 | t-01 | m03 | des-03 |
  | 1     | 1 | t-02 | m01 | des-01 |
  | 1     | 1 | t-02 | m02 | des-02 |
  | 1     | 1 | t-02 | m03 | des-03 |
  | 1     | 1 | t-03 | m01 | des-01 |
  | 1     | 1 | t-03 | m02 | des-02 |
  | 1     | 1 | t-03 | m03 | des-03 |
  | 1     | 2 | t-01 | m01 | des-01 |
  | 1     | 2 | t-01 | m02 | des-02 |
  | 1     | 2 | t-01 | m03 | des-03 |
  | 1     | 2 | t-02 | m01 | des-01 |
  | 1     | 2 | t-02 | m02 | des-02 |
  | 1     | 2 | t-02 | m03 | des-03 |
  | 1     | 2 | t-03 | m01 | des-01 |
  | 1     | 2 | t-03 | m02 | des-02 |
  | 1     | 2 | t-03 | m03 | des-03 |
  | 1     | 3 | t-01 | m01 | des-01 |
  | 1     | 3 | t-01 | m02 | des-02 |
  | 1     | 3 | t-01 | m03 | des-03 |
  | 1     | 3 | t-02 | m01 | des-01 |
  | 1     | 3 | t-02 | m02 | des-02 |
  | 1     | 3 | t-02 | m03 | des-03 |
  | 1     | 3 | t-03 | m01 | des-01 |
  | 1     | 3 | t-03 | m02 | des-02 |
  | 1     | 3 | t-03 | m03 | des-03 |
  | 2     | 1 | t-01 | m01 | des-01 |
  | 2     | 1 | t-01 | m02 | des-02 |
  | 2     | 1 | t-01 | m03 | des-03 |
  | 2     | 1 | t-02 | m01 | des-01 |
  | 2     | 1 | t-02 | m02 | des-02 |
  | 2     | 1 | t-02 | m03 | des-03 |
  | 2     | 1 | t-03 | m01 | des-01 |
  | 2     | 1 | t-03 | m02 | des-02 |
  | 2     | 1 | t-03 | m03 | des-03 |
  | 2     | 2 | t-01 | m01 | des-01 |
  | 2     | 2 | t-01 | m02 | des-02 |
  | 2     | 2 | t-01 | m03 | des-03 |
  | 2     | 2 | t-02 | m01 | des-01 |
  | 2     | 2 | t-02 | m02 | des-02 |
  | 2     | 2 | t-02 | m03 | des-03 |
  | 2     | 2 | t-03 | m01 | des-01 |
  | 2     | 2 | t-03 | m02 | des-02 |
  | 2     | 2 | t-03 | m03 | des-03 |
  | 2     | 3 | t-01 | m01 | des-01 |
  | 2     | 3 | t-01 | m02 | des-02 |
  | 2     | 3 | t-01 | m03 | des-03 |
  | 2     | 3 | t-02 | m01 | des-01 |
  | 2     | 3 | t-02 | m02 | des-02 |
  | 2     | 3 | t-02 | m03 | des-03 |
  | 2     | 3 | t-03 | m01 | des-01 |
  | 2     | 3 | t-03 | m02 | des-02 |
  | 2     | 3 | t-03 | m03 | des-03 |
  | 3     | 1 | t-01 | m01 | des-01 |
  | 3     | 1 | t-01 | m02 | des-02 |
  | 3     | 1 | t-01 | m03 | des-03 |
  | 3     | 1 | t-02 | m01 | des-01 |
  | 3     | 1 | t-02 | m02 | des-02 |
  | 3     | 1 | t-02 | m03 | des-03 |
  | 3     | 1 | t-03 | m01 | des-01 |
  | 3     | 1 | t-03 | m02 | des-02 |
  | 3     | 1 | t-03 | m03 | des-03 |
  | 3     | 2 | t-01 | m01 | des-01 |
  | 3     | 2 | t-01 | m02 | des-02 |
  | 3     | 2 | t-01 | m03 | des-03 |
  | 3     | 2 | t-02 | m01 | des-01 |
  | 3     | 2 | t-02 | m02 | des-02 |
  | 3     | 2 | t-02 | m03 | des-03 |
  | 3     | 2 | t-03 | m01 | des-01 |
  | 3     | 2 | t-03 | m02 | des-02 |
  | 3     | 2 | t-03 | m03 | des-03 |
  | 3     | 3 | t-01 | m01 | des-01 |
  | 3     | 3 | t-01 | m02 | des-02 |
  | 3     | 3 | t-01 | m03 | des-03 |
  | 3     | 3 | t-02 | m01 | des-01 |
  | 3     | 3 | t-02 | m02 | des-02 |
  | 3     | 3 | t-02 | m03 | des-03 |
  | 3     | 3 | t-03 | m01 | des-01 |
  | 3     | 3 | t-03 | m02 | des-02 |
  | 3     | 3 | t-03 | m03 | des-03 |
  When I curl it as json using "POST" to "http://service.planeonline.local/plane" 
  Then I should get:
    """
    {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":81,"success":81,"failed":0},"results":[{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"2","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"3","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"1","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"2","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"1","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"2","title":"t-03","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-01","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-01","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-01","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-02","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-02","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-02","model":"m03","description":"des-03","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-03","model":"m01","description":"des-01","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-03","model":"m02","description":"des-02","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"3","make":"3","title":"t-03","model":"m03","description":"des-03","id":"???"}}]}
    """