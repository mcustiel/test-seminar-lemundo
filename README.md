# test-seminar-lemundo
This is a dummy project for the automated testing seminar at Lemundo

## Requirements

### 1. Set translation texts

#### 1.1 No previous translation

* Having no translations in database for de_DE locale
* When a PUT request to /locale/de_DE/translation is sent
* With the json string: `{"id" : "tomato", "text" : "I am a tomato"}`
* Then the translation is saved into database and I see the json response: `{ "id": "tomato", "locale": "de_DE", "text": "I am a tomato" } `

#### 1.2 Existing translation with different locale and translation id

* Having created in database the translation from 1.1
* When a PUT request to /locale/de_DE/translation is sent
* With the json string: `{"id" : "potato", "text" : "I am a potato"}`
* Then the translation is saved into database and I see the json response: `{ "id": "potato", "locale": "de_DE", "text": "I am a potato" } ` and the translation from 1.1 remains unmodified

#### 1.3 Existing translation with same locale and translation id

* Having created in database the translation from 1.1
* When a PUT request to /locale/de_DE/translation is sent
* With the json string: `{"id" : "tomato", "text" : "I am a not tomato anymore"}`
* Then the translation is saved into database and I see the json response: `{ "id": "tomato", "locale": "de_DE", "text": "I am not a tomato anymore" } ` and the one from 1.1 was overwritten

### 2. List translation texts

#### 2.1 No translations created

* Having no translations in database for de_DE locale
* When a GET request is sent to /locale/de_DE/translation
* Then I see the json response: `[]`

#### 2.2 Translations were created

* Having created in database the translations from 1.1 and 1.2
* When a GET request is sent to /locale/de_DE/translation
* Then I see the json response: `{"tomato": "I am a tomato", "potato": "I am a potato"}`

### 3. Get translation texts

#### 3.1 Translation does not exist

* Having no translation in database for de_DE locale with id tomato
* When a GET request is sent to /locale/de_DE/translation/tomato
* Then I see an http response with status code 404 and body `No translation found`

#### 3.2 Translation exists

* Having created in database the translation from 1.1
* When a GET request is sent to /locale/de_DE/translation/tomato
* Then I see an http response with status code 404 and body `I am a tomato`

### 4. Delete translation texts

#### 4.1 Translation exists

* Having a translation in database for de_DE locale with id tomato
* When a DELETE request is sent to /locale/de_DE/translation/tomato
* Then I see an http response with status code 200 and empty body and the translation is removed from database

#### 4.2 Translation does not exist

* Having no translation in database for de_DE locale with id tomato
* When a DELETE request is sent to /locale/de_DE/translation/tomato
* Then I see an http response with status code 200 and empty body

### 5. Errors

#### 5.1 Server errors

* Having a correct state in server
* When an error happens during processing
* Then I see a json response `{"errorMessage" : the-message, "errorInfo": exception-info}` and status code 500

#### 5.2 Bad request errors

* Having a correct state in server
* When a request is incorrect
* Then I see a http response witht status code 400 and containing the error message in the body

### 6. Constraints

All constraints violations must cause the application to return 400 error.

* Translation Id
 * Not empty
 * Min length = 3
 * Max length = 32
 * Alphanumeric string
* Translation text
 * Not empty
* Locale
 * Must be one of: `de_DE, de_CH, de_AT, en_GB` 
