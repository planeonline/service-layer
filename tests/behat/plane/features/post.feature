Feature: post
  In order to test plane's POST methods
  As an API consumer
  I need to be able to post planes using RESTFul protocol

Scenario: Post a single plane to the service layer
  Given having the following list:
  | user  | make   | title   | description |
  | 1     | 1 | Skyhawk | Introducing the world’s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for.
  When I curl it as json using "POST" to "http://service.planeonline.local/plane" 
  Then I should get:
    """
    {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":1,"success":1,"failed":0},"results":[{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"Skyhawk","description":"Introducing the world\u2019s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for","id":"???"}}]}
    """

Scenario: Post multiple plane to the service layer
  Given having the following list:
  | user  | make   | title   | description |
  | 1     | 1 | Skyhawk | Introducing the world’s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for. |
  | 1     | 1 | TURBO SKYLANE JT-A | With 30-to-40-percent lower fuel burn the cost savings that the Turbo Skylane JT-A offers is as impressive as the technology that makes it possible. With worldwide fuel availability, a quieter engine, and advanced avionics with single-lever power, the Turbo Skylane JT-A is a piston pilot’s dream. The new 227-horsepower compression-ignition engine also delivers greater range or higher payload, delivering performance without sacrificing efficiency. |
  | 1     | 1 | TURBO STATIONAIR | The turbocharged muscle of the Turbo Stationair yields shorter takeoff distances, faster climbs, higher altitudes, greater speed, and utilitarian payload capacity for making the best of both work and play. Optional pontoons will turn your Turbo Stationair amphibious as well. Whether you’re bringing your son or daughter home from college, carrying commuters across great lakes, hauling fish in the wilds of Alaska, or saving lives in the Andes, the Turbo Stationair is the strong, quick, agile aircraft that will get you there. |
  When I curl it as json using "POST" to "http://service.planeonline.local/plane" 
  Then I should get:
    """
    {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":3,"success":3,"failed":0},"results":[{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"Skyhawk","description":"Introducing the world\u2019s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for.","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"TURBO SKYLANE JT-A","description":"With 30-to-40-percent lower fuel burn the cost savings that the Turbo Skylane JT-A offers is as impressive as the technology that makes it possible. With worldwide fuel availability, a quieter engine, and advanced avionics with single-lever power, the Turbo Skylane JT-A is a piston pilot\u2019s dream. The new 227-horsepower compression-ignition engine also delivers greater range or higher payload, delivering performance without sacrificing efficiency.","id":"???"}},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"TURBO STATIONAIR","description":"The turbocharged muscle of the Turbo Stationair yields shorter takeoff distances, faster climbs, higher altitudes, greater speed, and utilitarian payload capacity for making the best of both work and play. Optional pontoons will turn your Turbo Stationair amphibious as well. Whether you\u2019re bringing your son or daughter home from college, carrying commuters across great lakes, hauling fish in the wilds of Alaska, or saving lives in the Andes, the Turbo Stationair is the strong, quick, agile aircraft that will get you there.","id":"???"}}]}
    """

Scenario: Post a single plane to the service layer with a single validation issue
  Given having the following list:
    | user  | make   | title   | description |
    | 1     | 1 | Sky | Introducing the world’s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for.
  When I curl it as json using "POST" to "http://service.planeonline.local/plane"
  Then I should get:
  """
  {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":1,"success":0,"failed":1},"results":[{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":[{"title":"Minimum number of characters for title is 4"}]}]}
  """

Scenario: Post a single plane to the service layer with a couple of validation issue on a single field
  Given having the following list:
    | user  | make   | title   | description |
    | 1     | 12345678901ABCD | Skyhawk | Introducing the world’s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for.
  When I curl it as json using "POST" to "http://service.planeonline.local/plane"
  Then I should get:
  """
  {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":1,"success":0,"failed":1},"results":[{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":[{"make":"Value of field 'make' must be numeric"},{"make":"Maximum length for make id is (11)"}]}]}
  """

Scenario: Post multiple plane to the service layer with a single validation issue
  Given having the following list:
    | user  | make   | title   | description |
    | 1     | A | Skyhawk | Introducing the world’s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for. |
    | 1     | 1 | TTx | With superior safety and performance engineered into the design DNA of the aircraft, every inch of the TTx has been meticulously refined for an aerodynamic purity that contributes to its jet-like handling. Manufactured in all-composite materials, the TTx is a true high-performance aircraft. With built-in oxygen, 102-gallon fuel capacity, touch-screen glass avionics, and available satellite radios, the TTx is an exceptionally well-equipped flying machine. Its class-leading 235-knot maximum cruise speed sets it solidly above every other aircraft in its class. |
    | 1     | 1 | CARAVAN | The Caravan’s speed and range capabilities are unusually high for a cargo hauler. Its reliability and versatility in all weather conditions and terrains have made the Caravan famous for its rugged utility and flexibility. This durable workhorse will take to the sky from rough, unprepared, and short airstrips. Its reliability even in remote areas, at high altitudes, and in hot climates is unbeatable. |
  When I curl it as json using "POST" to "http://service.planeonline.local/plane"
  Then I should get:
  """
  {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":3,"success":1,"failed":2},"results":[{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":[{"make":"Value of field 'make' must be numeric"}]},{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":[{"title":"Minimum number of characters for title is 4"}]},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"CARAVAN","description":"The Caravan\u2019s speed and range capabilities are unusually high for a cargo hauler. Its reliability and versatility in all weather conditions and terrains have made the Caravan famous for its rugged utility and flexibility. This durable workhorse will take to the sky from rough, unprepared, and short airstrips. Its reliability even in remote areas, at high altitudes, and in hot climates is unbeatable.","id":"???"}}]}
  """

Scenario: Post multiple plane to the service layer with a number of validation issue on different records and fields
  Given having the following list:
    | user  | make   | title   | description |
    | 1     | ABCDEFGHIJKLMNOP | SK | Introducing the world’s most popular aircraft. With more than 43,000 aircraft with several model variants delivered, the Skyhawk is the best-selling, most-flown plane ever built. It also enjoys a distinguished reputation as the safest general aviation aircraft available. The Skyhawk is a top performer, showcasing the agility, stability, and durable strength that Cessna is famous for. |
    | 1     | 1 | GRAND CARAVAN AX | With 30-to-40-percent lower fuel burn the cost savings that the Turbo Skylane JT-A offers is as impressive as the technology that makes it possible. With worldwide fuel availability, a quieter engine, and advanced avionics with single-lever power, the Turbo Skylane JT-A is a piston pilot’s dream. The new 227-horsepower compression-ignition engine also delivers greater range or higher payload, delivering performance without sacrificing efficiency. |
    | 1     |  | TURBO STATIONAIR | The |
  When I curl it as json using "POST" to "http://service.planeonline.local/plane"
  Then I should get:
  """
  {"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"POST","results":3,"success":1,"failed":2},"results":[{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":[{"make":"Value of field 'make' must be numeric"},{"make":"Maximum length for make id is (11)"},{"title":"Minimum number of characters for title is 4"}]},{"metadata":{"status":"created","code":"201","model":"Plane"},"result":{"user":"1","make":"1","title":"GRAND CARAVAN AX","description":"With 30-to-40-percent lower fuel burn the cost savings that the Turbo Skylane JT-A offers is as impressive as the technology that makes it possible. With worldwide fuel availability, a quieter engine, and advanced avionics with single-lever power, the Turbo Skylane JT-A is a piston pilot\u2019s dream. The new 227-horsepower compression-ignition engine also delivers greater range or higher payload, delivering performance without sacrificing efficiency.","id":"???"}},{"metadata":{"status":"bad request","code":"400","model":"Plane"},"result":[{"make":"Value of field 'make' must be numeric"},{"make":"Minimum length for make id is (1)"},{"description":"Minimum number of characters for description is 10"},{"description":"Minimum number of characters for description is 5"}]}]}
  """