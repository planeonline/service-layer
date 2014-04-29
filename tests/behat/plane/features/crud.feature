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
    {"metadata":{"status":201},"messages":{"plane":{"created":[{"id":1,"user":1,"make":1,"title":"Skyhawk","model":"127","description":"The Cessna 172 Skyhawk is a four-seat, single-engine, high-wing fixed-wing aircraft made by the Cessna Aircraft Company. First flown in 1955, more Cessna 172s have been built than any other aircraft.","lastupdated":"server-time","status":0}]}}}
    """