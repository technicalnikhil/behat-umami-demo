Feature: Search feature

  Background: to open the homepage
    Given I am on the homepage

  Scenario Outline: to test the Search functionality of Website
    When I fill in "keys" with "<items>"
    And I press "Search"
    Then I should see "<output>"

    Examples:
    |items              |output                           |
    |pasta              |Super easy vegetarian pasta bake |
    |samosa             |Your search yielded no results.  |
    |                   |Please enter some keywords.      |


