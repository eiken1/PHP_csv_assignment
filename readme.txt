1. How to use the application

- Move the folder, containing the assignment, to HTDOCS, in your XAMPP folder

- Open XAMPP and enable APACHE and navigate to localhost/thisfolder and either /index.html or /PHP/data.php

- To start the application, navigate to the Data upload page (PHP/data.php) and upload a CSV file from the /UnloadedCsv folder

- My recommended upload file is data2.csv, as it has been the file i've used the most and it has also affected some of the application, specifically the coursename column in PHP/courses.php

- If the file was uploaded successfully, you will get a feedback message telling you so and you can navigate to the other pages

- Moving to the courses page, you will see a list of all courses and its properties

- Similarly, moving to the students page, you will see a list of all students and its properties

2. Duplicate validation

- If you want to check for validation, upload the csv file unvalidatedCsv.csv in the folder UnloadedCsv. It contains duplicate csv data, that should be filtered when uploaded. Then either check the data showcased on the students or courses page, or check each of the csv files in the CsvData folder.
