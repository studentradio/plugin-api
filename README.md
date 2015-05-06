## SRA JSON API
<img align="left" width="250px" height="250px" src="http://www.studentradio.org.uk/wp-content/uploads/2014/08/SRA-Logo-No-Text.png" />

This is a plugin for the Student Radio Assocation Website that provides a RESTful API to recieve results from external applications. 

We've put it on GitHub to encourage other developers to chip in, and also to manage what aspects of the API would be most useful to the end user. 

If you have a question, bug, or an enhancment request please list is as an 'issue'. 

## Privacy Guidelines

The SRA Website contains a mixture of publically accessible data, and private membership data that is protected by the Data Protection Act 1998. As such any API method that allows results to private email or telephone numbers requires a call to the Auth api first. (More details on that soon!)

## Contributing

Thank you for considering contributing to the SRA JSON API. Feel free to fork, and send a pull request. If your code adhere's to the above privacy guidelines and falls inline with the SRA's ambitions we will implement it into the SRA Website.

### License

This API is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Examples

To access the API use: `http://www.studentradio.org.uk/api/v1/<controller>/<method>`
EG. http://www.studentradio.org.uk/api/v1/stations/get_station/?slug=dcufm
