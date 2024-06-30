# MiniCampaignApp


![Logo](public/Images/logo.png)


## Project Overview:

MiniCampaignApp is designed to streamline the management and processing of email campaigns, providing an efficient and user-friendly interface for campaign creation, management, and monitoring.

## Features:

### User Authentication:

    1. Secure user registration and login via Laravel Breeze.

    2. Password reset functionality.

### Campaign Management:

    1. Create, Edit, View, Delete Campaigns: Users can easily manage their campaigns through a user-friendly interface.

    2. CSV Upload and Validation: Users can upload CSV files with campaign records. The system validates these files for errors and duplicates.

    3. Email Queuing and Processing: Automated queuing of emails for campaign records, ensuring timely delivery.

    4. Status Updates: Real-time updates on the status of campaign records - pending, processed, and failed.

### Email Notifications:

    1. Automated notifications to campaign owners upon completion of email processing.

    2. Error notifications for failed email deliveries.
    Queue Management:

    3. Efficient handling of high-volume email sending.

    4. Comprehensive logging and error handling.


### Best Practices Implemented:

    1. SOLID Principles: Ensuring scalable and maintainable code by adhering to SOLID principles.

    2. Design Patterns: Using repository and service patterns for clear separation of concerns and improved testability.

    3. Security: Validating all inputs and securing data through proper authentication and authorization mechanisms.

    4. Error Handling: Implementing robust error handling and logging for easier debugging and monitoring.

    5. Queue Management: Efficiently managing email processing queues using Laravel's built-in queue system.

### Development Tools & Technologies:

    Backend: Laravel 10
    Frontend: Vue.js, Inertia.js Tailwind CSS
    Email Service: Laravel Built mailer
    Database: MySQL
    Version Control: Git, GitHub

