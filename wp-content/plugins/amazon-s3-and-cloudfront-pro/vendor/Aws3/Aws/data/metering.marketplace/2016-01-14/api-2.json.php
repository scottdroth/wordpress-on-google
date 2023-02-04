<?php

namespace DeliciousBrains\WP_Offload_Media\Aws3;

// This file was auto-generated from sdk-root/src/data/metering.marketplace/2016-01-14/api-2.json
return ['version' => '2.0', 'metadata' => ['apiVersion' => '2016-01-14', 'endpointPrefix' => 'metering.marketplace', 'jsonVersion' => '1.1', 'protocol' => 'json', 'serviceFullName' => 'AWSMarketplace Metering', 'serviceId' => 'Marketplace Metering', 'signatureVersion' => 'v4', 'signingName' => 'aws-marketplace', 'targetPrefix' => 'AWSMPMeteringService', 'uid' => 'meteringmarketplace-2016-01-14'], 'operations' => ['BatchMeterUsage' => ['name' => 'BatchMeterUsage', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'BatchMeterUsageRequest'], 'output' => ['shape' => 'BatchMeterUsageResult'], 'errors' => [['shape' => 'InternalServiceErrorException'], ['shape' => 'InvalidProductCodeException'], ['shape' => 'InvalidUsageDimensionException'], ['shape' => 'InvalidTagException'], ['shape' => 'InvalidUsageAllocationsException'], ['shape' => 'InvalidCustomerIdentifierException'], ['shape' => 'TimestampOutOfBoundsException'], ['shape' => 'ThrottlingException'], ['shape' => 'DisabledApiException']]], 'MeterUsage' => ['name' => 'MeterUsage', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'MeterUsageRequest'], 'output' => ['shape' => 'MeterUsageResult'], 'errors' => [['shape' => 'InternalServiceErrorException'], ['shape' => 'InvalidProductCodeException'], ['shape' => 'InvalidUsageDimensionException'], ['shape' => 'InvalidTagException'], ['shape' => 'InvalidUsageAllocationsException'], ['shape' => 'InvalidEndpointRegionException'], ['shape' => 'TimestampOutOfBoundsException'], ['shape' => 'DuplicateRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'CustomerNotEntitledException']]], 'RegisterUsage' => ['name' => 'RegisterUsage', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'RegisterUsageRequest'], 'output' => ['shape' => 'RegisterUsageResult'], 'errors' => [['shape' => 'InvalidProductCodeException'], ['shape' => 'InvalidRegionException'], ['shape' => 'InvalidPublicKeyVersionException'], ['shape' => 'PlatformNotSupportedException'], ['shape' => 'CustomerNotEntitledException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalServiceErrorException'], ['shape' => 'DisabledApiException']]], 'ResolveCustomer' => ['name' => 'ResolveCustomer', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'ResolveCustomerRequest'], 'output' => ['shape' => 'ResolveCustomerResult'], 'errors' => [['shape' => 'InvalidTokenException'], ['shape' => 'ExpiredTokenException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalServiceErrorException'], ['shape' => 'DisabledApiException']]]], 'shapes' => ['AllocatedUsageQuantity' => ['type' => 'integer', 'max' => 2147483647, 'min' => 0], 'BatchMeterUsageRequest' => ['type' => 'structure', 'required' => ['UsageRecords', 'ProductCode'], 'members' => ['UsageRecords' => ['shape' => 'UsageRecordList'], 'ProductCode' => ['shape' => 'ProductCode']]], 'BatchMeterUsageResult' => ['type' => 'structure', 'members' => ['Results' => ['shape' => 'UsageRecordResultList'], 'UnprocessedRecords' => ['shape' => 'UsageRecordList']]], 'Boolean' => ['type' => 'boolean'], 'CustomerAWSAccountId' => ['type' => 'string', 'max' => 255, 'min' => 1, 'pattern' => '^[0-9]+$'], 'CustomerIdentifier' => ['type' => 'string', 'max' => 255, 'min' => 1, 'pattern' => '[\\s\\S]+'], 'CustomerNotEntitledException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'DisabledApiException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'DuplicateRequestException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'ExpiredTokenException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InternalServiceErrorException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true, 'fault' => \true], 'InvalidCustomerIdentifierException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidEndpointRegionException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidProductCodeException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidPublicKeyVersionException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidRegionException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidTagException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidTokenException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidUsageAllocationsException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'InvalidUsageDimensionException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'MeterUsageRequest' => ['type' => 'structure', 'required' => ['ProductCode', 'Timestamp', 'UsageDimension'], 'members' => ['ProductCode' => ['shape' => 'ProductCode'], 'Timestamp' => ['shape' => 'Timestamp'], 'UsageDimension' => ['shape' => 'UsageDimension'], 'UsageQuantity' => ['shape' => 'UsageQuantity'], 'DryRun' => ['shape' => 'Boolean'], 'UsageAllocations' => ['shape' => 'UsageAllocations']]], 'MeterUsageResult' => ['type' => 'structure', 'members' => ['MeteringRecordId' => ['shape' => 'String']]], 'NonEmptyString' => ['type' => 'string', 'pattern' => '[\\s\\S]+'], 'Nonce' => ['type' => 'string', 'max' => 255, 'pattern' => '[\\s\\S]*'], 'PlatformNotSupportedException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'ProductCode' => ['type' => 'string', 'max' => 255, 'min' => 1, 'pattern' => '^[-a-zA-Z0-9/=:_.@]*$'], 'RegisterUsageRequest' => ['type' => 'structure', 'required' => ['ProductCode', 'PublicKeyVersion'], 'members' => ['ProductCode' => ['shape' => 'ProductCode'], 'PublicKeyVersion' => ['shape' => 'VersionInteger'], 'Nonce' => ['shape' => 'Nonce']]], 'RegisterUsageResult' => ['type' => 'structure', 'members' => ['PublicKeyRotationTimestamp' => ['shape' => 'Timestamp'], 'Signature' => ['shape' => 'NonEmptyString']]], 'ResolveCustomerRequest' => ['type' => 'structure', 'required' => ['RegistrationToken'], 'members' => ['RegistrationToken' => ['shape' => 'NonEmptyString']]], 'ResolveCustomerResult' => ['type' => 'structure', 'members' => ['CustomerIdentifier' => ['shape' => 'CustomerIdentifier'], 'ProductCode' => ['shape' => 'ProductCode'], 'CustomerAWSAccountId' => ['shape' => 'CustomerAWSAccountId']]], 'String' => ['type' => 'string'], 'Tag' => ['type' => 'structure', 'required' => ['Key', 'Value'], 'members' => ['Key' => ['shape' => 'TagKey'], 'Value' => ['shape' => 'TagValue']]], 'TagKey' => ['type' => 'string', 'max' => 100, 'min' => 1, 'pattern' => '^[a-zA-Z0-9+ -=._:\\/@]+$'], 'TagList' => ['type' => 'list', 'member' => ['shape' => 'Tag'], 'max' => 5, 'min' => 1], 'TagValue' => ['type' => 'string', 'max' => 256, 'min' => 1, 'pattern' => '^[a-zA-Z0-9+ -=._:\\/@]+$'], 'ThrottlingException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'Timestamp' => ['type' => 'timestamp'], 'TimestampOutOfBoundsException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'errorMessage']], 'exception' => \true], 'UsageAllocation' => ['type' => 'structure', 'required' => ['AllocatedUsageQuantity'], 'members' => ['AllocatedUsageQuantity' => ['shape' => 'AllocatedUsageQuantity'], 'Tags' => ['shape' => 'TagList']]], 'UsageAllocations' => ['type' => 'list', 'member' => ['shape' => 'UsageAllocation'], 'max' => 2500, 'min' => 1], 'UsageDimension' => ['type' => 'string', 'max' => 255, 'min' => 1, 'pattern' => '[\\s\\S]+'], 'UsageQuantity' => ['type' => 'integer', 'max' => 2147483647, 'min' => 0], 'UsageRecord' => ['type' => 'structure', 'required' => ['Timestamp', 'CustomerIdentifier', 'Dimension'], 'members' => ['Timestamp' => ['shape' => 'Timestamp'], 'CustomerIdentifier' => ['shape' => 'CustomerIdentifier'], 'Dimension' => ['shape' => 'UsageDimension'], 'Quantity' => ['shape' => 'UsageQuantity'], 'UsageAllocations' => ['shape' => 'UsageAllocations']]], 'UsageRecordList' => ['type' => 'list', 'member' => ['shape' => 'UsageRecord'], 'max' => 25, 'min' => 0], 'UsageRecordResult' => ['type' => 'structure', 'members' => ['UsageRecord' => ['shape' => 'UsageRecord'], 'MeteringRecordId' => ['shape' => 'String'], 'Status' => ['shape' => 'UsageRecordResultStatus']]], 'UsageRecordResultList' => ['type' => 'list', 'member' => ['shape' => 'UsageRecordResult']], 'UsageRecordResultStatus' => ['type' => 'string', 'enum' => ['Success', 'CustomerNotSubscribed', 'DuplicateRecord']], 'VersionInteger' => ['type' => 'integer', 'min' => 1], 'errorMessage' => ['type' => 'string']]];
