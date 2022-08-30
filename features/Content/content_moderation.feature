@javascript @api
Feature: Content moderation feature

  Background: to go to homepage
    Given I am on the homepage

  Scenario: verify admin is able change moderation states from draft to Published and Published to archived
    Given I am logged in as a user with the "Administrator" role
    When I visit "/node/add/page"
    And I press the button "URL alias"
    And I fill in the following:
      |Title                 |Security News               |
      |URL alias             |/security_news              |
    And I press "Save"
    Then I should see "Basic page Security News has been created."

    Given I am an anonymous user
    When I visit "/security_news"
    Then I should see "Access denied"

    Given I am logged in as a user with the "Administrator" role
    When I visit "/security_news"
    And select "published" from "new_state"
    And I press "Apply"
    Then I should see "The moderation state has been updated."

    Given I am an anonymous user
    When I visit "/security_news"
    Then I should see "Security News"

    Given I am logged in as a user with the "Administrator" role
    When I visit "/security_news"
    And I click "Edit"
    And select "archived" from "moderation_state[0][state]"
    And I press "Save"
    Then I should see "has been updated."

    Given I am an anonymous user
    When I visit "/security_news"
    Then I should see "Access denied"

  Scenario: verify default state at the time of content creation is Draft or not
    Given I am logged in as a user with the "Administrator" role
    When I visit "/node/add/page"
    Then I should see default status as "Draft"
