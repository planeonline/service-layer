#Feature: crud
#  In order to test plane's CRUD functinlity
#  As an API consumer
#  I need to be able to post, get, put and delete planes using RESTFul protocol
#
#Scenario: Post a plane to the service layer
#  Given having the following list:
#  | user  | make   | title   | model | description |
#  | 1     | 1 | Skyhawk | 127   | The Cessna 172 Skyhawk is a four-seat, single-engine, high-wing fixed-wing aircraft made by the Cessna Aircraft Company. First flown in 1955, more Cessna 172s have been built than any other aircraft.|
#  When I curl it as json using "POST" to "http://service.planeonline.local/plane"
#  Then I should get:
#    """
#    {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":1,"success":1,"failed":0},"results":[{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"Skyhawk","model":"127","description":"The Cessna 172 Skyhawk is a four-seat, single-engine, high-wing fixed-wing aircraft made by the Cessna Aircraft Company. First flown in 1955, more Cessna 172s have been built than any other aircraft.","id":"???"}}]}
#    """
#
#Scenario: Post multiple plane to the service layer
#  Given having the following list:
#  | user  | make   | title   | model | description |
#  | 2     | 3 | Skyhawk | 127 | The Cessna 172 001 |
#  | 2     | 3 | Sky | 127 | The Cessna 172 002 |
#  | 2     | 3 | Skyhawk | 127 | The Cessna 172 003 |
#  When I curl it as json using "POST" to "http://service.planeonline.local/plane"
#  Then I should get:
#    """
#    {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":3,"success":2,"failed":1},"results":[{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"Skyhawk","model":"127","description":"The Cessna 172 001","id":"???"}},{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":[{"title":"Minimum number of characters for title is 4"}]},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"2","make":"3","title":"Skyhawk","model":"127","description":"The Cessna 172 003","id":"???"}}]}
#    """
#
