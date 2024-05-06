# Getting Started

## Installation

### Using git

You can clone the repository using the following command
```bash
git clone https://github.com/kim-project/framework.git
```
After the command is complete you will see a `framework` Folder  
  
- Delete the `docs` and `.git` folder inside the `framwork` folder
- Copy the `.env.example` file to the `.env` file using the following command in the terminal in the folder
    ```bash
    cp .env.example .env
    ```
    or copy and rename it manually

And you are good to go

### Manually

Go to our Github Page [Kim Framework](https://github.com/kim-project/framework.git) select `Code` and Select `Download ZIP`
  
After the download was complete extract it and do the following in the extracted folder
  
- Delete the `docs` folder inside the folder
- Copy the `.env.example` file to the `.env` file using the following command in the terminal in the folder
    ```bash
    cp .env.example .env
    ```
    or copy and rename it manually

And you are good to go

## Starting Server

There are multiple ways to start the framework if you have an `Apache` server (or you are using `XAMPP`) you can use that server to run the framework or you can use the built in `Kim` commands

### Using Kim Command

To start the server execute the following code in the Framework's root directory
```bash
php Kim serve
```
or use one of its alias commands
```bash
php Kim start
php Kim s
```
now the project is running on `127.0.0.1:8000`

### Using Apache (XAMPP)

To use `Apache` (`XAMPP`) put it in the server's root http directory (Usually `htdocs`) and the code should be running fine on the `127.0.0.1`

## Updating

You can update the framework for patches and bug fixes through the following command
```bash
php Kim update
```
or use one of its alias commands
```bash
php Kim upgrade
php Kim u
```
now wait for the update to complete and you are good to go
