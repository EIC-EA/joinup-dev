@api
Feature:
  - As a visitor, in order to register to Joinup, I have to accept the site's
    'Legal notice', otherwise I cannot register.
  - As registered user, in order to login when a new version of 'Legal notice'
    has been released, I have to accept the new version, otherwise I cannot
    login.
  - As a moderator, I want to be able to release new versions of 'Legal notice'
    or to edit the existing ones.
  - As a visitor, in order to send a support request on the `/contact` page, I
    have to accept the site's 'Legal notice', otherwise I cannot submit.

  Background:
    Given the following legal document version:
      | Document     | Label | Published | Acceptance label                                                                                   | Content                                                    |
      | Legal notice | 1.1   | yes       | I have read and accept the <a href="[entity_legal_document:url]">[entity_legal_document:label]</a> | The information on this site is subject to a disclaimer... |

  Scenario: Anonymous user can read the Legal notice.
    Given I am on the homepage
    When I click "Legal notice" in the Footer region
    Then I should see the heading "Legal notice"
    And I should see "The information on this site is subject to a disclaimer..."

  Scenario: User login when a new 'Legal notice' version is released.
    Given CAS users:
      | Username | E-mail           | Password |
      | Rick     | rick@example.com | secretz  |

    And I am on the homepage
    And I click "Sign in with EU Login"
    And I fill in "E-mail address" with "rick@example.com"
    And I fill in "Password" with "secretz"
    And I press "Log in"
    Then I should see the warning message "You must accept this agreement before continuing."

    # After accepting the agreement the user can continue.
    When I check "I have read and accept the Legal notice"
    And I press "Submit"
    Then I should not see the warning message "You must accept this agreement before continuing."

    # Login again to check that the acceptance enforcement has gone.
    Given I click "Sign out"
    And I go to homepage
    And I click "Sign in with EU Login"

    When I fill in "E-mail address" with "rick@example.com"
    And I fill in "Password" with "secretz"
    And I press "Log in"
    Then I should not see the warning message "You must accept this agreement before continuing."
    When I click "My account"
    Then I should see the heading "Rick"

    # While Rick navigates on the site a new version is created and published.
    Given the following legal document versions:
      | Document     | Label | Published | Acceptance label    | Content             |
      | Legal notice | 2.0   | no        | Accept Version 2.0! | Version 2.0 content |
    And the version "2.0" of "Legal notice" legal document is published

    # There is a cacheability issue with the front page caused by
    # \Drupal\joinup_cas_mock_server\Config\JoinupCasMockServerConfigOverrider::getCacheableMetadata and after the
    # config metadata was removed and replaced, this issue started happening.
    When the cache has been cleared
    And I go to homepage
    Then I should see the warning message "You must accept this agreement before continuing."
    And I should see the heading "Legal notice"
    And I should see "Version 2.0 content"
    And I should see "Accept Version 2.0!"

    # Try to navigate.
    When I click "My account"
    Then I should see the warning message "You must accept this agreement before continuing."

    # Sign-out is allowed.
    When I click "Sign out"
    Then I should not see the warning message "You must accept this agreement before continuing."
    And I should see the link "Sign in"

    And I click "Sign in with EU Login"
    And I fill in "E-mail address" with "rick@example.com"
    And I fill in "Password" with "secretz"

    When I press "Log in"

    Then I should see the warning message "You must accept this agreement before continuing."
    And I should see the heading "Legal notice"
    And I should see "Version 2.0 content"

    When I check "Accept Version 2.0!"
    And I press "Submit"
    Then I should not see the warning message "You must accept this agreement before continuing."

    # Clean up the user that was created manually during the scenario.
    Then I delete the "Rick" user

  Scenario: Moderator tasks.
    Given I am logged in as a moderator
    When I click "Legal"
    Then I should see the heading "Legal notice versions"
    And I should see the link "New version"
    And the row "1.1" is selected

    When I press "Set published version"
    Then I should see the success message "No changes have been made."

    When I click "Edit"
    Then I should see the heading "Edit 1.1"

    Given I fill in "Title" with "1.1.1"
    And I fill in "Acceptance label" with "Accept!"

    When I press "Save"
    Then I should see "1.1.1"
    And the row "1.1.1" is selected

    Given I click "New version"
    And I fill in "Title" with "v2.0"
    And I fill in "Document text" with "New rules..."
    And I fill in "Acceptance label" with "Accept 2.0!"

    When I press "Save"
    Then the row "1.1.1" is selected
    But the row "v2.0" is not selected

    When I select the "v2.0" row
    And I press "Set published version"
    Then I should see the success message "Legal notice v2.0 has been published."
    And the row "v2.0" is selected
    But the row "1.1.1" is not selected

    When I click "Legal notice" in the Footer region
    Then I should see the heading "Legal notice"
    And I should see "New rules..."

    # As this version has been created via UI it should be manually deleted.
    And I delete the version "v2.0" of document "Legal notice"

  Scenario: Anonymous using the support contact form.
    Given I am on "/contact"
    Then I should see "I have read and accept the Legal notice"

    Given I fill in "First name" with "Eleanor"
    And I fill in "Last name" with "Rigby"
    And I fill in "Organisation" with "Lonely People"
    And I fill in "E-mail address" with "depression@example.com"
    And I select "Legal issue" from "Category"
    And I fill in "Subject" with "All the lonely people where do they all come from?"
    And I fill in "Message" with "Father McKenzie, wiping the dirt / From his hands as he walks from the grave / No one was saved"
    Then I wait for the honeypot time limit to pass

    When I press "Submit"
    Then I should see the error message "You must accept the Legal notice in order to use our platform."

    But I check "I have read and accept the Legal notice"
    Then I wait for the honeypot time limit to pass
    When I press "Submit"
    Then I should see the success message "Your message has been submitted. Thank you for your feedback."

    Given I am logged in as an "authenticated user"
    And I accept the "Legal notice" agreement

    When I am on "/contact"
    Then I should not see "I have read and accept the Legal notice"
