import apiServices from 'src/core/service/api';

export default function initializeApiServices() {
    // Add custom api service providers
    apiServices.forEach((ApiService) => {
        const serviceContainer = this.getContainer('service');
        const factoryContainer = this.getContainer('factory');
        const initContainer = this.getContainer('init');

        const apiServiceFactory = factoryContainer.apiService;
        const service = new ApiService(initContainer.httpClient, serviceContainer.loginService);
        const serviceName = service.name;
        apiServiceFactory.register(serviceName, service);

        this.addServiceProvider(serviceName, () => {
            return service;
        });
    });
}
