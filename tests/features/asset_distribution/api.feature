@api @group-a
Feature: Distribution API
  In order to manage solutions programmatically
  As a backend developer
  I need to be able to use the Distribution API

  Scenario: Programmatically create a distribution
    Given the following collection:
      | title            | Asset distribution collection API foo |
      | logo             | logo.png                              |
      | moderation       | yes                                   |
      | content creation | facilitators                          |
      | state            | validated                             |
    And the following solution:
      | title            | Asset distribution solution           |
      | collection       | Asset distribution collection API foo |
      | description      | Asset distribution sample solution    |
      | documentation    | text.pdf                              |
      | content creation | registered users                      |
      | landing page     | http://foo-example.com/landing        |
      | webdav creation  | no                                    |
      | webdav url       | http://joinup.eu/solution/foo/webdav  |
      | wiki             | http://example.wiki/foobar/wiki       |
      | state            | validated                             |
    And the following release:
      | title          | Asset distribution asset release   |
      | description    | Asset distribution sample solution |
      | documentation  | text.pdf                           |
      | release number | 1                                  |
      | release notes  | Changed release                    |
      | is version of  | Asset distribution solution        |
    And the following distribution:
      | title       | Asset distribution entity foo         |
      | description | Asset distribution sample description |
      | access url  | test.zip                              |
      | parent      | Asset distribution asset release      |
    Then I should have 1 solution
    And I should have 1 release
    And I should have 1 distribution

  Scenario: Programmatically create a distribution using only the mandatory fields
    Given the following collection:
      | title            | Asset distribution short API bar |
      | logo             | logo.png                         |
      | moderation       | yes                              |
      | content creation | facilitators                     |
      | state            | validated                        |
    And the following solution:
      | title            | AD first solution mandatory short |
      | collection       | Asset distribution short API bar  |
      | description      | Another sample solution           |
      | content creation | members                           |
      | state            | validated                         |
    And the following release:
      | title          | AD first release                   |
      | description    | Asset distribution sample solution |
      | release number | 1                                  |
      | release notes  | Changed release                    |
      | is version of  | AD first solution mandatory short  |
    And the following distribution:
      | title       | Asset distribution entity foo short   |
      | description | Asset distribution sample description |
      | access url  | test.zip                              |
      | parent      | AD first release                      |
    Then I should have 1 solution
    And I should have 1 release
    And I should have 1 distribution
