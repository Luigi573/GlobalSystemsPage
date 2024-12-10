# GlobalSystemsPage

A website made for the company **Global Systems** to help them display their work online and offer their services to potential clients.

## Front End
This project uses Bootstrap Frameworks and JQuery to speed up development by helping with responsive design and send requests to the server using AJAX respectively.

- **Main Page (index.html):**
  - Contains a summary of the services and projects.
  - Provides information about the company, with an "About Us" section at the top.
- **Services:**
  - Offers a detailed summary of the various services provided by the company.
- **Projects:**
  - Includes a photo gallery showcasing completed projects by the company.
  - **Contact Us**
  - Form that lets the user send an email to the company in order to request a service. It sends a CC to the sender

## Back End

- **Email Service**
  - PhP class used to send Emails thanks to Mailer library. It uses the address: globalsystems.bot@gmail.com
- **EmailServiceTest**
  - Test class made with phpUnit framework to verify EmailService.php's proper functionality


## Running the Website Locally

1. Switch to the `localHost` branch.
2. Open the `index.html` file with a Live Server extension in VS Code or a similar tool.

### Activating Email Sending on `contactForm.html`

1. Navigate to the `php` folder.
2. Run the EmailService using the command line:
   ```bash
   php -S localhost:8000
   ```

## Developed By

**Xavier Arián Olivares Sánchez**
