# LeanPHP Micro Framework

<p align="center">
  <a href="https://yourprojectwebsite.com" target="_blank">
    <img src="https://raw.githubusercontent.com/yourusername/LeanPHP/master/path/to/your/logo.svg" width="400" alt="LeanPHP Logo">
  </a>
</p>

LeanPHP is a lightweight, PHP-based micro-framework designed to accelerate the development of minimum viable products (MVPs) for startups and tech enterprises. It aligns with agile and lean startup methodologies, allowing for rapid development and deployment with minimal costs.

![License](https://img.shields.io/github/license/yourusername/LeanPHP)
![GitHub stars](https://img.shields.io/github/stars/yourusername/LeanPHP?style=social)
![GitHub forks](https://img.shields.io/github/forks/yourusername/LeanPHP?style=social)
![GitHub issues](https://img.shields.io/github/issues/yourusername/LeanPHP)
![GitHub pull requests](https://img.shields.io/github/issues-pr/yourusername/LeanPHP)
![Twitter Follow](https://img.shields.io/twitter/follow/yourtwitter?style=social)

## Key Features

- **Agile Compatibility**: Supports continuous integration and continuous deployment (CI/CD) practices.
- **Lean Startup Ready**: "Download, run, and develop" model for frequent product releases.
- **Cost Efficiency**: Minimizes total cost of ownership, no expensive licenses required.
- **Rapid Development and Deployment**: Simplifies setup and speeds up development processes.

## Support Us

We love our contributors and supporters! If you'd like to contribute financially, please visit our [GitHub Sponsors](https://github.com/sponsors/yourusername) page or our [Patreon](https://patreon.com/yourusername).

Alternatively, you can make a one-time donation via [Buy Me a Coffee](https://www.buymeacoffee.com/yourusername).

## Who's Using LeanPHP?

Here are some of the companies that have implemented LeanPHP:

- Company A
- Company B
- Company C

We are proud to see LeanPHP in action in such varied industries!

## Join Our Community

- **Twitter**: Follow us on [Twitter](https://twitter.com/yourtwitter)
- **Discord**: Join our [Discord](https://discord.gg/yourinvite) community to chat with the developers and other users.

## Getting Started

### Prerequisites

- PHP 7.4 or higher

### Installation

You can start using LeanPHP with just a few simple steps, no package manager or complex setup required:

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/LeanPHP.git
   cd LeanPHP

**Or download the ZIP file directly from GitHub and extract it**

Run your PHP server:Navigate to the LeanPHP directory and start your PHP server:
```bash
   git clone https://github.com/yourusername/LeanPHP.git
   cd LeanPHP
```

### Usage
Here's a simple example to get you started, demonstrating how to set up a route and handle it through a controller:

**Define a Route in routes.php:**
```
$router->get('users', 'UserController', 'getAllUsers');
```

**Define a Route in routes.php:**
```
$router->get('users', 'UserController', 'getAllUsers');
```

**Implement the Controller Method:In UserController.php:**
```
public function getAllUsers($request, $response) {
    try {
        $users = $this->userModel->getAll();
        $response->withJson(['data' => $users])->send();
    } catch (Exception $e) {
        $this->errorHandler->handle($e);
    }
}
```

**Implement the Model Method:In UserModel.php:**
```
public function getAll() {
    // Assume $db is your database connection
    return $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
}
```

**Define Environment File**

In LeanPHP, configuring your environment is straightforward. You simply specify which environment file to load in `index.php`:

```php
$envFile = '.env.local';
```

**Configure Environment File**

```
APP_NAME=LeanPHP

APP_FOLDER=/leanphp/
APP_ENV=production
APP_SECRET=
APP_DEBUG=false
APP_URL=https://localhost

DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=leanphp
DB_USERNAME="root"
DB_PASSWORD=

```

## LeanPHP in 3 Minute :)

[![Introduction](http://img.youtube.com/vi/VIDEO_ID/0.jpg)](http://www.youtube.com/watch?v=VIDEO_ID)
![Diagram](https://github.com/yourusername/LeanPHP/blob/master/assets/setup-diagram.png)

## Contributing

Contributions are what make the open-source community such an incredible place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

Refer to [CONTRIBUTING.md](CONTRIBUTING.md) for more details on how you can contribute.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Additional Links

- **GitHub Marketplace**: [Visit our GitHub Marketplace page](https://github.com/marketplace/yourproduct)
- **VS Code Marketplace**: [Get our VS Code extension](https://marketplace.visualstudio.com/items?itemName=yourusername.leanphp-extension)
- **Product Hunt**: [Check us out on Product Hunt](https://www.producthunt.com/posts/leanphp)
#
