{
	"info": {
		"name": "JREAM",
		"_postman_id": "e225c1a8-9970-e52e-d61e-9aa46388b763",
		"description": "",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
	},
	"item": [
		{
			"name": "Auth",
			"description": "",
			"item": [
				{
					"name": "api/auth/register (Local Account)",
					"event": [
						{
							"listen": "prerequest",
							"script": {
								"type": "text/javascript",
								"exec": [
									"pm.globals.set(\"alias\", \"testBotA\");",
									"pm.globals.set(\"email\", \"testbotA@jream.com\");",
									"pm.globals.set(\"password\", \"fishFry1\");",
									"",
									"// For Change Tests",
									"pm.globals.set(\"aliasChange\", \"testBotZ\");",
									"pm.globals.set(\"emailChange\", \"testbotZ@jream.com\");",
									"pm.globals.set(\"passwordChange\", \"fishFry2\");",
									"",
									""
								]
							}
						},
						{
							"listen": "test",
							"script": {
								"type": "text/javascript",
								"exec": [
									"",
									"",
									""
								]
							}
						}
					],
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"alias\": \"{{alias}}\",\n\t\"email\": \"{{email}}\",\n\t\"password\": \"{{password}}\",\n\t\"confirm_password\": \"{{password}}\",\n\t\"newsletter\": \"on\",\n\t\"api_testing\": \"winter\"\n}"
						},
						"url": {
							"raw": "{{url}}/api/auth/register",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"auth",
								"register"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/auth/logout",
					"request": {
						"method": "GET",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": ""
						},
						"url": {
							"raw": "{{url}}/api/auth/logout",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"auth",
								"logout"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/auth/login",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"email\": \"{{email}}\",\n\t\"password\": \"{{password}}\"\n}"
						},
						"url": {
							"raw": "{{url}}/api/auth/login",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"auth",
								"login"
							]
						},
						"description": ""
					},
					"response": []
				}
			]
		},
		{
			"name": "User",
			"description": "",
			"item": [
				{
					"name": "api/user/updateTimezone",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"timezone\": \"UTC\"\n}"
						},
						"url": {
							"raw": "{{url}}/api/user/updatetimezone",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"user",
								"updatetimezone"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/user/updateEmail",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"email\": \"{{emailChange}}\",\n\t\"confirm_email\": \"{{emailChange}}\"\n}"
						},
						"url": {
							"raw": "{{url}}/api/user/updateemail",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"user",
								"updateemail"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/user/updateAlias",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"alias\": \"{{aliasChange}}\"\n}"
						},
						"url": {
							"raw": "{{url}}/api/user/updatealias",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"user",
								"updatealias"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/user/updateNotifications",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"email_notifications\": 0,\n\t\"system_notifications\": 0,\n\t\"newsletter_subscribe\": 0\n}"
						},
						"url": {
							"raw": "{{url}}/api/user/updatenotifications",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"user",
								"updatenotifications"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/user/updateNotifications (Revert)",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"email_notifications\": 1,\n\t\"system_notifications\": 1,\n\t\"newsletter_subscribe\": 1\n}"
						},
						"url": {
							"raw": "{{url}}/api/user/updatenotifications",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"user",
								"updatenotifications"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/user/updatePassword",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"current_password\": \"{{password}}\",\n\t\"password\": \"{{passwordChange}}\",\n\t\"confirm_password\": \"{{passwordChange}}\"\n}"
						},
						"url": {
							"raw": "{{url}}/api/user/updatepassword",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"user",
								"updatepassword"
							]
						},
						"description": ""
					},
					"response": []
				}
			]
		},
		{
			"name": "Purchase",
			"description": "",
			"item": [
				{
					"name": "api/purchase/free",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"product_id\": 10\n}"
						},
						"url": {
							"raw": "{{url}}/api/purchase/free",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"purchase",
								"free"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/product/purchase/free (Test API Stripe)",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"product_id\": 1\t\n}"
						},
						"url": {
							"raw": "{{url}}/api/purchase/free",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"purchase",
								"free"
							]
						},
						"description": ""
					},
					"response": []
				}
			]
		},
		{
			"name": "Course",
			"description": "",
			"item": [
				{
					"name": "api/course/updateProgress",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"contentId\": 6,\n\t\"productId\": 1,\n\t\"action\": 5,\n\t\"value\": 1\n}"
						},
						"url": {
							"raw": "{{url}}/api/course/updateProgress",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"course",
								"updateProgress"
							]
						},
						"description": ""
					},
					"response": []
				},
				{
					"name": "api/course/updateProgress (Revert)",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"contentId\": 6,\n\t\"productId\": 1,\n\t\"action\": 5,\n\t\"value\": 0\n}"
						},
						"url": {
							"raw": "{{url}}/api/course/updateProgress",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"course",
								"updateProgress"
							]
						},
						"description": ""
					},
					"response": []
				}
			]
		},
		{
			"name": "Cleanup",
			"description": "",
			"item": [
				{
					"name": "api/user/deleteaccount",
					"request": {
						"method": "POST",
						"header": [
							{
								"key": "Content-Type",
								"value": "application/json"
							}
						],
						"body": {
							"mode": "raw",
							"raw": "{\n\t\"confirm\": \"delete\",\n\t\"understand\": \"on\"\n}"
						},
						"url": {
							"raw": "{{url}}/api/user/deleteaccount",
							"host": [
								"{{url}}"
							],
							"path": [
								"api",
								"user",
								"deleteaccount"
							]
						},
						"description": ""
					},
					"response": []
				}
			]
		}
	]
}