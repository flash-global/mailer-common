# Service Mailer - Common

[![GitHub release](https://img.shields.io/github/release/flash-global/mailer-common.svg?style=for-the-badge)](README.md)

## Table of contents
- [Entities](#entities)
- [Contribution](#contribution)

## Entities

### Mail
| Properties    | Type              | Required | Default value |
|---------------|-------------------|----------|---------------|
| subject          | `string`      | Yes       |               |
| textBody          | `string`      | Yes       |               |
| htmlBody     | `string`        | Yes       |          |
| sender     | `array`          | Yes       |               |
| replyTo     | `array`          | No       |               |
| recipients     | `array`          | Yes       |               |
| cc     | `array`          | No       |               |
| bcc     | `array`          | No       |               |
| attachments     | `array`          | No       |               |
| dispositionNotificationTo     | `array`          | No       |            |


### Attachment
| Properties    | Type              | Required | Default value |
|---------------|-------------------|----------|---------------|
| attachmentFilename          | `string`      | Yes       |               |
| mimeType          | `string`      | Yes       |               |
| isEmbedded     | `bool`        | Yes       | False         |
| id     | `string`          | No       |               |


## Contribution
As FEI Service, designed and made by OpCoding. The contribution workflow will involve both technical teams. Feel free to contribute, to improve features and apply patches, but keep in mind to carefully deal with pull request. Merging must be the product of complete discussions between Flash and OpCoding teams :) 



