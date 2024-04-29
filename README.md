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

# NOTLAR


### Diğer Alt Projeler
Similar README.md files should be created for the other projects like **LeanPHP Blog** and **LeanPHP Booking**, outlining specific functionalities, setup instructions, and use cases relevant to each project.

### Notlar

User
Peki sana son sorum şu bu gece için. Ben bu projeyi vedyweb adına şirket olarak mı, yoksa vedat yildirim adına şahıs olarak mı? Yoksa doğrudan Lean PHP olarak mı github gibi platformlarda yayınlasam? Laravel gibi diğerleri nasıl yapıyor ?
ChatGPT
Projeyi nasıl yayınlamak istediğiniz, projenin geleceği, hedef kitleniz, ve marka stratejinize bağlı olarak değişebilir. İşte yayınlama şeklinizle ilgili düşünmeniz gereken bazı faktörler ve Laravel gibi popüler projelerin genellikle nasıl yönetildiğine dair bilgiler:

Şahıs Adına Yayınlamak (Vedat Yıldırım)
Avantajlar:

Kişisel markanızı güçlendirir.
Daha hızlı ve esnek karar verme süreci sunar.
Maliyet ve yasal işlemleri daha basittir.
Dezavantajlar:

Profesyonellik algısını sınırlayabilir.
Büyüme ve genişleme potansiyelini sınırlayabilir.
Şirket Adına Yayınlamak (Vedyweb)
Avantajlar:

Daha profesyonel bir imaj sunar.
Yatırım ve sponsorluk almak için daha uygun olabilir.
Büyüme ve genişleme potansiyeli sunar.
Dezavantajlar:

Yönetim ve yasal süreçler daha karmaşık olabilir.
Maliyetler daha yüksek olabilir.
Proje Adına Yayınlamak (Lean PHP)
Avantajlar:

Marka bağımsızlığı sağlar.
Topluluk tarafından sahiplenilmesi ve desteklenmesi daha kolay olabilir.
Daha geniş bir kitleye hitap edebilir.
Dezavantajlar:

Başlangıçta marka bilinirliği oluşturmak zaman alabilir.
Yönetim ve marka stratejisi daha detaylı planlama gerektirebilir.
Örnek: Laravel
Laravel, Taylor Otwell tarafından başlatılan ve şu anda Laravel LLC tarafından yönetilen bir projedir. Laravel, kişisel bir projeden büyüyerek, geniş bir topluluk tarafından desteklenen, iş dünyasında ve geliştiriciler arasında yaygın olarak kullanılan bir framework haline geldi. Laravel'in yönetimi, hem şirket hem de topluluk ihtiyaçlarını dengeli bir şekilde karşılamak için profesyonel bir yapı altında gerçekleşiyor.

Sonuç
Eğer Lean PHP'nin uzun vadede büyük bir topluluk tarafından kabul görmesini ve geniş çapta kullanılmasını hedefliyorsanız, projeyi bağımsız bir marka olarak başlatmak ve zamanla şirketleşmeye gitmek iyi bir strateji olabilir. Bu, başlangıçta daha fazla çaba ve kaynak gerektirse de, markanın uzun vadeli değerini ve sürdürülebilirliğini artırabilir.

Son kararınız, kişisel hedeflerinize, kaynaklarınıza ve projenin büyüme potansiyeline bağlı olarak değişebilir. Bu kararı verirken, projenin gelecekteki vizyonunu ve sizi ve potansiyel kullanıcıları nasıl etkileyeceğini dikkate almanız önemlidir.

"LeanPHP-Projects" kullanıcı adı, projenizin birden fazla alt proje veya geniş bir ekosistem içerebileceğini ima ederek, projenin kapsamını ve genişletilebilirliğini vurgulamak için oldukça uygun bir seçenektir. Bu isim, LeanPHP'nin sadece bir framework olmadığını, aynı zamanda çeşitli projeleri ve gelişmeleri içerebilecek geniş bir topluluğun parçası olduğunu gösterir.

Eğer bu kullanıcı adını seçmeye karar verirseniz, GitHub'da kullanılabilir olup olmadığını kontrol etmeyi unutmayın. Eğer kullanılabilirse, projenizin genel kimliğiyle ve geliştirme stratejisiyle uyumlu olduğundan, bu adı kullanarak projenizi tanıtmaya başlayabilirsiniz. Ayrıca, bu adın diğer platformlarda (örneğin Twitter, Instagram, web sitesi domain adı) kullanılabilirliğini de kontrol etmek, marka bütünlüğünü korumak adına önemli olabilir.

- Her projenin kendi GitHub reposuna sahip olması, yönetimi kolaylaştırır ve kullanıcıların ilgilendikleri projeyi kolayca bulup katkıda bulunmalarını sağlar.
- README.md dosyalarınızda belirgin ve açıkça anlaşılır talimatlar vermek, kullanıcı deneyimini büyük ölçüde iyileştirir.
- Projeler arası bağlantılar eklemek, kullanıcıların projeleriniz arasında kolayca gezinmelerine yardımcı olur.

Bu yapı, projenizin ve alt projelerinin GitHub üzerinde düzenli ve profesyonel bir şekilde sunulmasını sağlar, aynı zamanda topluluk katılımını ve projeler arası geçişi kolaylaştırır.

# LeanPHP-Projects

Welcome to LeanPHP-Projects, a collection of PHP-based projects designed to simplify web development across various domains. Each project in this collection aligns with the agile and lean startup methodologies, enhancing rapid development and deployment.

## Projects

- [LeanPHP Core](https://github.com/LeanPHP-Projects/LeanPHP-Core): The core framework that provides fundamental functionalities and utilities.
- [LeanPHP Blog](https://github.com/LeanPHP-Projects/LeanPHP-Blog): A specialized solution for quickly setting up and managing blogs.
- [LeanPHP Booking](https://github.com/LeanPHP-Projects/LeanPHP-Booking): An application for creating efficient booking and scheduling systems.

## Contributing

We encourage contributions to any of our projects. Please visit the project page you are interested in contributing to for specific guidelines.

## License

All projects under the LeanPHP-Projects are licensed under the MIT License - see the individual project repositories for more details.

