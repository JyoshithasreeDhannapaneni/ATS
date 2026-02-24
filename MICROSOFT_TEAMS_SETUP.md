# Microsoft Teams Integration Setup Guide

This guide explains how to configure the Microsoft Teams integration for automatic meeting creation when scheduling calls in the calendar.

## Overview

When you schedule a call, meeting, or interview in the calendar, the system will automatically create a Microsoft Teams meeting and add the join link to the calendar event.

## Prerequisites

1. A Microsoft 365 account with Teams enabled
2. Azure Active Directory (Azure AD) access to register an application
3. Admin permissions to grant API permissions

## Setup Steps

### Step 1: Register Application in Azure AD

1. Go to [Azure Portal](https://portal.azure.com/)
2. Navigate to **Azure Active Directory** > **App registrations**
3. Click **New registration**
4. Enter a name (e.g., "ATS Teams Integration")
5. Select **Accounts in this organizational directory only**
6. Click **Register**
7. Note down the **Application (client) ID** and **Directory (tenant) ID**

### Step 2: Create Client Secret

1. In your app registration, go to **Certificates & secrets**
2. Click **New client secret**
3. Enter a description and expiration period
4. Click **Add**
5. **IMPORTANT**: Copy the secret value immediately (you won't be able to see it again)

### Step 3: Grant API Permissions

1. In your app registration, go to **API permissions**
2. Click **Add a permission**
3. Select **Microsoft Graph**
4. Select **Application permissions**
5. Add the following permissions:
   - `OnlineMeetings.ReadWrite.All` - To create Teams meetings
   - `User.Read.All` - To read user information (optional, for organizer details)
6. Click **Add permissions**
7. Click **Grant admin consent** for your organization

### Step 4: Configure the Application

1. Open your `config.php` file
2. Add the following configuration:

```php
// Microsoft Teams Integration
define('MS_TEAMS_ENABLED', true);
define('MS_TEAMS_CLIENT_ID', 'YOUR_CLIENT_ID_HERE');
define('MS_TEAMS_CLIENT_SECRET', 'YOUR_CLIENT_SECRET_HERE');
define('MS_TEAMS_TENANT_ID', 'YOUR_TENANT_ID_HERE');
define('MS_TEAMS_USER_ID', 'user@yourdomain.com'); // Email of the meeting organizer
```

Replace:
- `YOUR_CLIENT_ID_HERE` with your Application (client) ID from Step 1
- `YOUR_CLIENT_SECRET_HERE` with your client secret value from Step 2
- `YOUR_TENANT_ID_HERE` with your Directory (tenant) ID from Step 1
- `user@yourdomain.com` with the email address of the user who will be the meeting organizer

### Step 5: Run Database Migration

Run the SQL migration script to add the Teams meeting link column:

```sql
ALTER TABLE `calendar_event` 
ADD COLUMN `teams_meeting_link` TEXT COLLATE utf8_unicode_ci NULL DEFAULT NULL 
AFTER `public`;
```

Or execute the file: `db/upgrade-add-teams-meeting-link.sql`

## How It Works

1. When you schedule a **Call**, **Meeting**, or **Interview** in the calendar:
   - The system automatically creates a Microsoft Teams meeting
   - The Teams meeting join link is stored in the calendar event
   - The link can be accessed when viewing the calendar event

2. Event types that trigger Teams meeting creation:
   - **Call** (Type 100)
   - **Meeting** (Type 300)
   - **Interview** (Type 400)

3. Other event types (Email, Personal, Other) do not create Teams meetings.

## Troubleshooting

### Teams meetings are not being created

1. **Check configuration**: Verify all settings in `config.php` are correct
2. **Check permissions**: Ensure admin consent was granted for API permissions
3. **Check logs**: Review PHP error logs for integration errors
4. **Verify user ID**: Ensure `MS_TEAMS_USER_ID` is a valid user email in your organization

### Common Errors

- **401 Unauthorized**: Check client ID, secret, and tenant ID
- **403 Forbidden**: Ensure API permissions are granted and admin consent is provided
- **404 Not Found**: Verify the user ID/email exists in your organization

## Security Notes

- Never commit `config.php` with real credentials to version control
- Keep your client secret secure and rotate it regularly
- Use application permissions (not delegated) for server-to-server authentication
- Consider using Azure Key Vault for storing secrets in production

## Support

For issues with:
- **Azure AD setup**: Contact your Azure AD administrator
- **Integration issues**: Check PHP error logs and verify configuration
- **API permissions**: Ensure all required permissions are granted with admin consent
